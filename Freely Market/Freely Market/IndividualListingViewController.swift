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
    @IBOutlet var loadingIndicator: UIActivityIndicatorView!
    
    var lTitle = String()
    var image = String()
    var descr = String()
    var owner = String()
    var price = String()
    var btnText = String()

    override func viewDidLoad() {
        super.viewDidLoad()
        
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
        rentButton.setTitle(btnText, for: .normal)
        listingOwner.setTitle(owner, for: .normal)
        
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
        }
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