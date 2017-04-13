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
    @IBOutlet weak var listingOwner: UILabel!
    @IBOutlet weak var listingDescription: UITextView!
    @IBOutlet weak var rentButton: UIButton!
    
    var lTitle = String()
    var image = String()
    var descr = String()
    var owner = String()
    var price = String()

    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // Handlers for menu button
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        listingDescription.layer.cornerRadius = 5.0
        listingDescription.layer.shadowOffset = CGSize(width: 1, height: 1)
        listingDescription.layer.shadowOpacity = 0.1
        listingImage.layer.shadowOffset = CGSize(width: 1, height: 1)
        listingImage.layer.shadowOpacity = 0.1
        
        print(lTitle)
        print(image)
        print(descr)
        print(owner)
        print(price)
        
        
        listingTitle.text = lTitle
        //listingImage.image =
        listingOwner.text = owner
        
        print(" ")
//        print("title: " + listingTitle.text!)
//        print("Owner: " + listingOwner.text!)
//        print("Description: " + listingDescription.text!)
//        print("imagePath: " + imagePath)
    }
    
    func populateFields() {
//        let requestURL: NSURL = NSURL(string: "http://cgi.soic.indiana.edu/~team12/api/rentalListings.php")!
//        let urlRequest: NSMutableURLRequest = NSMutableURLRequest(url: requestURL as URL)
//        let session = URLSession.shared
//        let imageURL = URL(string: "http://cgi.soic.indiana.edu/~team12/images/" + imagePath)!
//        let task = session.dataTask(with: urlRequest as URLRequest) {
//            (data, response, error) -> Void in
//            
//            let httpResponse = response as! HTTPURLResponse
//            let statusCode = httpResponse.statusCode
//            if (statusCode == 200) {
//                do {
//                    let json = try JSONSerialization.jsonObject(with: data!, options: .allowFragments) as! [String:AnyObject]
//                    if let listings = json["listing"] as? [[String: AnyObject]] {
//                        for listing in listings {
//                            if let title = listing["item"] as? String {
//                                if let price = listing["price"] as? String {
//                                    if let picture = listing["picture"] as? String {
//                                        //self.RentalData.append([title, "$" + price, picture])
//                                    }
//                                }
//                            }
//                        }
//                    }
//                    DispatchQueue.main.async {
//                        //self.tableView.reloadData()
//                    }
//                } catch {
//                    print("Error with JSON: \(error)")
//                }
//            }
//        }
//        task.resume()
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destinationViewController.
        // Pass the selected object to the new view controller.
    }
    */

}
