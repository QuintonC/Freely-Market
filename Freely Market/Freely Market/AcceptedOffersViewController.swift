//
//  AcceptedOffersViewController.swift
//  Freely Market
//
//  Created by Austin Mitts on 4/19/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import Foundation
import UIKit

class AcceptedOffersViewController: UIViewController, UITableViewDataSource, UITableViewDelegate, PayPalPaymentDelegate {
    
    var environment:String = PayPalEnvironmentNoNetwork {
        willSet(newEnvironment) {
            if (newEnvironment != environment) {
                PayPalMobile.preconnect(withEnvironment: newEnvironment)
            }
        }
    }
    
    
    
    var ListingsData: [[String]] = []
    var selectedTitle = String()
    var selectedPrice = String()
    var selectedImage = String()
    var selectedDescr = String()
    var selectedOwner = String()
    var selectedType = String()
    var selectedBuyer = String()
    var selectedID = String()
    
    
    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var tableView: UITableView!
    
    var payPalConfig = PayPalConfiguration()
    override func viewDidLoad() {
        
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        
        super.viewDidLoad()
        
        offerListings()
        
        // Set up payPalConfig
        payPalConfig.acceptCreditCards = false
        payPalConfig.merchantName = "Freely Market"
        payPalConfig.merchantPrivacyPolicyURL = URL(string: "https://www.paypal.com/webapps/mpp/ua/privacy-full")
        payPalConfig.merchantUserAgreementURL = URL(string: "https://www.paypal.com/webapps/mpp/ua/useragreement-full")
        
        payPalConfig.languageOrLocale = Locale.preferredLanguages[0]
        
        payPalConfig.payPalShippingAddressOption = .both
        
    }
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        PayPalMobile.preconnect(withEnvironment: environment)
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return ListingsData.count
    }
    
    //function to get information on all rental listings in the database
    func offerListings() {
        let requestURL: NSURL = NSURL(string: "http://cgi.soic.indiana.edu/~team12/api/myPendingPayments.php")!
        let urlRequest: NSMutableURLRequest = NSMutableURLRequest(url: requestURL as URL)
        urlRequest.httpMethod = "POST"
        let postString = "user=\(USER)"
        urlRequest.httpBody = postString.data(using: String.Encoding.utf8)
        
        let session = URLSession.shared
        let task = session.dataTask(with: urlRequest as URLRequest) {
            (data, response, error) -> Void in
            
            let httpResponse = response as! HTTPURLResponse
            let statusCode = httpResponse.statusCode
            if (statusCode == 200) {
                do {
                    let json = try JSONSerialization.jsonObject(with: data!, options: .allowFragments) as! [String:AnyObject]
                    if let listings = json["offer"] as? [[String: AnyObject]] {
                        for listing in listings {
                            if let title = listing["item"] as? String {
                                if let price = listing["price"] as? String {
                                    if let picture = listing["picture"] as? String {
                                        if let descr = listing["descr"] as? String {
                                            if let owner = listing["owner"] as? String {
                                                if let buyer = listing["buyer"] as? String {
                                                    if let type = listing["type"] as? String {
                                                        if let listingID = listing["id"] as Optional {
                                                            let testID = String(describing: listingID)
                                                            self.ListingsData.append([title, "$" + price, picture, descr, owner, buyer, type, testID])
                                                            
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    DispatchQueue.main.async {
                        self.tableView.reloadData()
                    }
                } catch {
                    print("Error with JSON: \(error)")
                }
            }
        }
        task.resume()
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let listing = tableView.dequeueReusableCell(withIdentifier: "cell", for: indexPath) as! CellData
        listing.contentView.backgroundColor = UIColor.clear
        
        let cellStyle : UIView = UIView(frame: CGRect(x: 10, y: 8, width: self.view.frame.size.width - 20, height: 85))
        
        cellStyle.layer.backgroundColor = CGColor(colorSpace: CGColorSpaceCreateDeviceRGB(), components: [1.0, 1.0, 1.0, 0.5])
        cellStyle.layer.masksToBounds = false
        cellStyle.layer.cornerRadius = 5.0
        cellStyle.layer.shadowOffset = CGSize(width: 1, height: 1)
        cellStyle.layer.shadowOpacity = 0.1
        
        listing.contentView.addSubview(cellStyle)
        listing.contentView.sendSubview(toBack: cellStyle)
        
        listing.listingTitle.text = ListingsData[indexPath.row][0]
        print(ListingsData[indexPath.row][0])
        listing.listingPrice.text = ListingsData[indexPath.row][1]
        
        let imageURL = URL(string: "http://cgi.soic.indiana.edu/~team12/images/" + ListingsData[indexPath.row][2])!
        let session = URLSession(configuration: .default)
        
        let downloadPicTask = session.dataTask(with: imageURL) {
            (data, response, error) in
            if let e = error {
                print("Error download image: \(e)")
            } else {
                if (response as? HTTPURLResponse) != nil {
                    if let imageData = data {
                        DispatchQueue.main.async {
                            let picture = UIImage(data: imageData)
                            listing.listingImage.image = picture
                        }
                        
                    } else {
                        print("Couldn't get image: Image is nil")
                    }
                } else {
                    print("Couldn't get response code for some reason")
                }
            }
        }
        downloadPicTask.resume()
        return listing
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        //Set variables to pass to IndividualListingViewController
        selectedTitle = ListingsData[indexPath.row][0] as String
        selectedPrice = ListingsData[indexPath.row][1] as String
        selectedImage = ListingsData[indexPath.row][2] as String
        selectedDescr = ListingsData[indexPath.row][3] as String
        selectedOwner = ListingsData[indexPath.row][4] as String
        selectedBuyer = ListingsData[indexPath.row][5] as String
        selectedType = ListingsData[indexPath.row][6] as String
        selectedID = ListingsData[indexPath.row][7] as String
        
        //performSegue(withIdentifier: "myListingSegue", sender: self)
    }
    
    func tableView(_ tableView: UITableView, editActionsForRowAt indexPath: IndexPath) -> [UITableViewRowAction]? {
        let pay = UITableViewRowAction(style: .normal, title: "Pay Now") { action, index in
            self.pay(index: indexPath.row)
        }
        pay.backgroundColor = UIColor(red: 0.33, green: 0.85, blue: 0.5, alpha: 1.0)
        
        return [pay]
    }
    
    func pay(index: Int) {
        let title = ListingsData[index][0]
        let price = ListingsData[index][1].replacingOccurrences(of: "$", with: "", options: .literal, range: nil)
        let type = ListingsData[index][6]
        let id = ListingsData[index][7]
        
        let sku = type+"&"+id
        
        let item1 = PayPalItem(name: title, withQuantity: 1, withPrice: NSDecimalNumber(string: price), withCurrency: "USD", withSku: sku)
        
        
        let items = [item1]
        let subtotal = PayPalItem.totalPrice(forItems: items)
        
        // Optional: include payment details
        let shipping = NSDecimalNumber(string: "0.00")
        let tax = NSDecimalNumber(string: "0.00")
        let paymentDetails = PayPalPaymentDetails(subtotal: subtotal, withShipping: shipping, withTax: tax)
        
        let total = subtotal.adding(shipping).adding(tax)
        
        let payment = PayPalPayment(amount: total, currencyCode: "USD", shortDescription: title, intent: .sale)
        
        payment.items = items
        payment.paymentDetails = paymentDetails
        
        if (payment.processable) {
            let paymentViewController = PayPalPaymentViewController(payment: payment, configuration: payPalConfig, delegate: self)
            present(paymentViewController!, animated: true, completion: nil)
        }
        else {
            // This particular payment will always be processable. If, for
            // example, the amount was negative or the shortDescription was
            // empty, this payment wouldn't be processable, and you'd want
            // to handle that here.
            print("Payment not processalbe: \(payment)")
        }
    }
    
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if (segue.identifier == "myListingSegue") {
            //Create an instance of the NavigationController
            let navVC = segue.destination as? UINavigationController
            //Create an instance of the destination IndividualListingViewController
            let listingVC = navVC?.viewControllers.first as! IndividualListingViewController
            
            //give the variables in the destination values from the current viewcontroller
            listingVC.lTitle = selectedTitle
            listingVC.image = selectedImage
            listingVC.descr = selectedDescr
            listingVC.owner = selectedOwner
            listingVC.price = selectedPrice
            listingVC.btnText = "Rent Now - " + selectedPrice
            listingVC.listingType = selectedType
            
        }
    }
    
    
    
    @IBAction func logout(_ sender: AnyObject) {
        
        let alertController = UIAlertController(title: "Success", message: "You have been logged out.", preferredStyle: .alert)
        let OKAction = UIAlertAction(title: "OK", style: .default) {
            (action:UIAlertAction) in
            self.performSegue(withIdentifier: "logoutSegue", sender: self)
        }
        alertController.addAction(OKAction)
        self.present(alertController, animated: true, completion:nil)
        
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    func payPalPaymentDidCancel(_ paymentViewController: PayPalPaymentViewController) {
        print("PayPal Payment Cancelled")
        
        paymentViewController.dismiss(animated: true, completion: nil)
    }
    
    func payPalPaymentViewController(_ paymentViewController: PayPalPaymentViewController, didComplete completedPayment: PayPalPayment) {
        print("PayPal Payment Success !")
        paymentViewController.dismiss(animated: true, completion: { () -> Void in
            // send completed confirmaion to your server
            print("Here is your proof of payment:\n\n\(completedPayment.confirmation)\n\nSend this to your server for confirmation and fulfillment.")
            //get id of listing
            let item = completedPayment.items?[0] as! PayPalItem
            var identity = item.sku!.components(separatedBy: "&")
            let type = identity[0]
            let id = identity[1]
            
//##########################//##########################//##########################//##########################//##########################//##########################//##########################
//##########################//##########################//##########################//##########################//##########################//##########################//##########################
//##########################//##########################//##########################//##########################//##########################//##########################//##########################

            var request = URLRequest(url: URL(string: "http://cgi.soic.indiana.edu/~team12/api/updateTransaction.php")!)
            request.httpMethod = "POST"
            let postString = "id=\(id)&type=\(type)"
            
            request.httpBody = postString.data(using: String.Encoding.utf8)
            
            let task = URLSession.shared.dataTask(with: request as URLRequest) {
                (data, response, error) in
                
                if error != nil {
                    print("error is \(String(describing: error))")
                    return
                }
                
                var err: NSError?
                
                do {
                    let json = try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as? NSDictionary
                    
                    
                    if let parseJSON = json {
                        
                        let messageToDisplay:String = parseJSON["message"] as! String
                        let myAlert = UIAlertController(title: "Alert", message:messageToDisplay, preferredStyle: .alert)
                        
                        if messageToDisplay == "Could not update table" {
                            DispatchQueue.main.async {
                                let OKAction = UIAlertAction(title: "OK", style: .default) {
                                    (action:UIAlertAction) in
                                }
                                myAlert.addAction(OKAction)
                                self.present(myAlert, animated: true, completion: nil)
                            }
                        } else if messageToDisplay == "Update Success" {
                            DispatchQueue.main.async {
                                self.performSegue(withIdentifier: "transactionComplete", sender: self)
                            }
                        } else {
                            DispatchQueue.main.async {
                                let OKAction = UIAlertAction(title: "OK", style: .default) {
                                    (action:UIAlertAction) in
                                }
                                myAlert.addAction(OKAction)
                                self.present(myAlert, animated: true, completion: nil)
                            }
                        }
                    }
                } catch let error as NSError {
                    print(err = error)
                }
            }
            task.resume()
            
//##########################//##########################//##########################//##########################//##########################//##########################//##########################
//##########################//##########################//##########################//##########################//##########################//##########################//##########################
//##########################//##########################//##########################//##########################//##########################//##########################//##########################
            
            

        })
    }
    

    
    
}
