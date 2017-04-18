//
//  SaleListingsViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 4/5/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import UIKit

class SaleListingsViewController: UIViewController, UITableViewDataSource, UITableViewDelegate {
    
    var BuyData: [[String]] = []
    var selectedTitle = String()
    var selectedPrice = String()
    var selectedImage = String()
    var selectedDescr = String()
    var selectedOwner = String()
    
    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var tableView: UITableView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        BuyData = []
        
        self.tabBarController?.navigationItem.title = "Listings"
        
        // Do any additional setup after loading the view, typically from a nib.
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        buyListings()
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return BuyData.count
    }
    
    //function to get information on all buy listings in the database
    func buyListings() {
        let requestURL: NSURL = NSURL(string: "http://cgi.soic.indiana.edu/~team12/api/buyListings.php")!
        let urlRequest: NSMutableURLRequest = NSMutableURLRequest(url: requestURL as URL)
        let session = URLSession.shared
        let task = session.dataTask(with: urlRequest as URLRequest) {
            (data, response, error) -> Void in
            
            let httpResponse = response as! HTTPURLResponse
            let statusCode = httpResponse.statusCode
            if (statusCode == 200) {
                do {
                    let json = try JSONSerialization.jsonObject(with: data!, options: .allowFragments) as! [String:AnyObject]
                    if let listings = json["listing"] as? [[String: AnyObject]] {
                        for listing in listings {
                            if let title = listing["item"] as? String {
                                if let price = listing["price"] as? String {
                                    if let picture = listing["picture"] as? String {
                                        if let descr = listing["descr"] as? String {
                                            if let owner = listing["owner"] as? String {
                                                self.BuyData.append([title, "$" + price, picture, descr, owner])
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
        let buy = tableView.dequeueReusableCell(withIdentifier: "cell", for: indexPath) as! CellData
        buy.contentView.backgroundColor = UIColor.clear
        let cellStyle : UIView = UIView(frame: CGRect(x: 10, y: 8, width: self.view.frame.size.width - 20, height: 85))
        cellStyle.layer.backgroundColor = CGColor(colorSpace: CGColorSpaceCreateDeviceRGB(), components: [1.0, 1.0, 1.0, 0.5])
        cellStyle.layer.masksToBounds = false
        cellStyle.layer.cornerRadius = 5.0
        cellStyle.layer.shadowOffset = CGSize(width: 1, height: 1)
        cellStyle.layer.shadowOpacity = 0.1
        buy.contentView.addSubview(cellStyle)
        buy.contentView.sendSubview(toBack: cellStyle)
        buy.listingTitle.text = BuyData[indexPath.row][0]
        buy.listingPrice.text = BuyData[indexPath.row][1]
        let imageURL = URL(string: "http://cgi.soic.indiana.edu/~team12/images/" + BuyData[indexPath.row][2])!
        let session = URLSession(configuration: .default)
        let downloadPicTask = session.dataTask(with: imageURL) {
            (data, response, error) in
            if let e = error {
                print("Error download image: \(e)")
            } else {
                if (response as? HTTPURLResponse) != nil {
                    //print("Downloaded image with response code \(res.statusCode)")
                    if let imageData = data {
                        DispatchQueue.main.async {
                            let picture = UIImage(data: imageData)
                            buy.listingImage.image = picture
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
        return buy
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        //Set variables to pass to IndividualListingViewController
        selectedTitle = BuyData[indexPath.row][0] as String
        selectedPrice = BuyData[indexPath.row][1] as String
        selectedImage = BuyData[indexPath.row][2] as String
        selectedDescr = BuyData[indexPath.row][3] as String
        selectedOwner = BuyData[indexPath.row][4] as String
        
        performSegue(withIdentifier: "passSegue", sender: self)
    }
    
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if (segue.identifier == "passSegue") {
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
            listingVC.btnText = "Buy Now - " + selectedPrice
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
    
    
}
