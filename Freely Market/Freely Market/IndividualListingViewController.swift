//
//  IndividualListingViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 4/11/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import UIKit

class IndividualListingViewController: UIViewController {

    @IBOutlet weak var moreButton: UIBarButtonItem!
    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var listingTitle: UILabel!
    @IBOutlet weak var listingImage: UIImageView!
    @IBOutlet weak var listingOwner: UIButton!
    @IBOutlet weak var listingDescription: UITextView!
    @IBOutlet weak var rentButton: UIButton!
    @IBOutlet weak var viewOffersBtn: UIButton!
    @IBOutlet var loadingIndicator: UIActivityIndicatorView!
    @IBOutlet weak var editButton: UIBarButtonItem!
    
    var lTitle = String()
    var image = String()
    var descr = String()
    var owner = String()
    var price = String()
    var plainPrice = String()
    var btnText = String()

    var hideButton = false
    var hideViewOffers = true

    var listingType = String()
    
    var bid = String()
    var rid = String()
    var eid = String()
    var finID = String()
    
    var callURL = URL(string: "")

    override func viewDidLoad() {
        super.viewDidLoad()
        
        if owner == USER {
            editButton.isEnabled = true
            hideButton = true
            hideViewOffers = false
        } else if USERTYP == "2" {
            editButton.isEnabled = true
        }else {
            editButton.isEnabled = false
        }
        
        plainPrice = price.replacingOccurrences(of: "$", with: "")
        
        // Handlers for menu button
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        //Design for the description and image
        listingDescription.layer.cornerRadius = 5.0
        listingDescription.layer.shadowOffset = CGSize(width: 1, height: 1)
        listingDescription.layer.shadowOpacity = 0.1
        listingImage.layer.shadowOffset = CGSize(width: 1, height: 1)
        listingImage.layer.shadowOpacity = 0.1

        //Populate ui elements with data
        
        
        listingTitle.text = lTitle
        listingDescription.text = descr
        rentButton.isHidden = hideButton

        rentButton.setTitle(btnText, for: .normal)
        listingOwner.setTitle(owner, for: .normal)
        viewOffersBtn.isHidden = hideViewOffers
        
        
        //Start loading Activity Indicator
        loadingIndicator.startAnimating()
        
        //Get Listing Image from database and show it in listingImage
        let imageURL = URL(string: "http://cgi.soic.indiana.edu/~team12/images/" + image)
        let session = URLSession(configuration: .default)
        
        let downloadPicTask = session.dataTask(with: imageURL!) {
            (data, response, error) in
            if let e = error {
                print("Error download image: \(e)")
            } else {
                if (response as? HTTPURLResponse) != nil {
                    if let imageData = data {
                        DispatchQueue.main.async {
                            let picture = UIImage(data: imageData)
                            self.listingImage.image = picture
                            //Stop the loadingIndicator
                            self.loadingIndicator.removeFromSuperview()
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
        
        // GET ID OF THE LISTING
        if listingType == "Rental_Listing" {
            callURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getRID.php")
            rid = finID
        } else if listingType == "Equipment_Listing" {
            callURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getEID.php")
            eid = finID
        } else if listingType == "Buy_Listing" {
            callURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getBID.php")
            //deleteURL = URL(string: http://cgi.soic)
            bid = finID
        } else {
            print ("Something went wrong, could not retrieve ID of the listing.")
        }
        
        var request = URLRequest(url: callURL!)
        request.httpMethod = "POST"
        let postString = "item=\(lTitle)&price=\(plainPrice)&descr=\(descr)&picture=\(image)"
        
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
                    DispatchQueue.main.async {
                        let baseID = ("\(String(describing: parseJSON["ID:"]))")
                        let halfID = baseID.replacingOccurrences(of: "Optional(", with: "")
                        self.finID = halfID.replacingOccurrences(of: ")", with: "")
                    }
                }
            } catch let error as NSError {
                print(err = error)
            }
        }
        task.resume()
    }
    
    
    @IBAction func editTapped(_ sender: Any) {
        performSegue(withIdentifier: "editSegue", sender: self)
    }
    
    
    @IBAction func viewOffers(_ sender: Any) {
        performSegue(withIdentifier: "viewOffersSegue", sender: self)
    }
    
    

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if (segue.identifier == "messageOwner") {
            //Create instance of CreateNewConvoViewController
            let destinationVC = segue.destination as! CreateNewConvoViewController
            //Give CreateNewConvoViewController's textEntry a value
            destinationVC.username = owner
        } else if (segue.identifier == "editSegue") {
            
            let editVC = segue.destination as! EditListingViewController
            
            editVC.lTitle = listingTitle.text!
            editVC.image = image
            editVC.descr = listingDescription.text
            editVC.price = price
            editVC.listingType = listingType

        } else if (segue.identifier == "viewOffersSegue") {
            //Create an instance of the NavigationController
            let navVC = segue.destination as? UINavigationController
            //Create an instance of the destination IndividualListingViewController
            let destinationVC = navVC?.viewControllers.first as! OffersViewController
            
            destinationVC.offerBy = "junkData"
            destinationVC.type = self.listingType
            destinationVC.id = self.finID
            
            print("type: "+listingType)
            print("id: "+self.finID)

        }
    }
    
    // GET ID OF THE LISTING
//    func getProperId () {
//        if listingType == "Rental_Listing" {
//            callURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getRID.php")
//            rid = finID
//            print("rental")
//        } else if listingType == "Equipment_Listing" {
//            callURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getEID.php")
//            eid = finID
//            print("equipment")
//        } else if listingType == "Buy_Listing" {
//            callURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getBID.php")
//            bid = finID
//            print("buy")
//        } else {
//            print ("Something went wrong, could not retrieve ID of the listing.")
//        }
//        
//        var request = URLRequest(url: callURL!)
//        request.httpMethod = "POST"
//        let postString = "item=\(lTitle)&price=\(price)&descr=\(descr)&picture=\(image)"
//        
//        request.httpBody = postString.data(using: String.Encoding.utf8)
//        
//        let task = URLSession.shared.dataTask(with: request as URLRequest) {
//            (data, response, error) in
//            
//            if error != nil {
//                print("error is \(String(describing: error))")
//                return
//            }
//            
//            var err: NSError?
//            
//            do {
//                let json = try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as? NSDictionary
//                
//                if let parseJSON = json {
//                    DispatchQueue.main.async {
//                        let baseID = ("\(String(describing: parseJSON["ID:"]))")
//                        let halfID = baseID.replacingOccurrences(of: "Optional(", with: "")
//                        self.finID = halfID.replacingOccurrences(of: ")", with: "")
//                        print(self.finID)
//                    }
//                }
//            } catch let error as NSError {
//                print(err = error)
//            }
//        }
//        task.resume()
//    }
    
    
    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destinationViewController.
        // Pass the selected object to the new view controller.
    }
    */

}
