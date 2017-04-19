//
//  OffersViewController.swift
//  Freely Market
//
//  Created by Austin Mitts on 4/18/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import Foundation
import UIKit

class OffersViewController: UIViewController, UITableViewDataSource, UITableViewDelegate {
    
    var myOffers: [String] = []
    
    @IBOutlet weak var tableView: UITableView!
    @IBOutlet weak var menuButton: UIBarButtonItem!
    
    var offerBy = String()
    
    var type = String()
    var id = String()
    var bid = String()
    var rid = String()
    var eid = String()
    
    var callURL = URL(string: "")
    var acceptURL = URL(string: "")
    var declineURL = URL(string: "")
    
    override func viewDidLoad() {
        
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        
        super.viewDidLoad()
        
        //########################################################################################################################################################################################################
        if type == "Rental_Listing" {
            callURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getRentalOffers.php")
            acceptURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/acceptRentalOffer.php")
            declineURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/declineRentalOffer.php")
        } else if type == "Equipment_Listing" {
            callURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getEquipmentOffers.php")
            acceptURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/acceptEquipmentOffer.php")
            declineURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/declineEquipmentOffer.php")
        } else if type == "Buy_Listing" {
            callURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getBuyOffers.php")
            acceptURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/acceptBuyOffer.php")
            declineURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/declineBuyOffer.php")
        } else {
            print ("Something went wrong, could not retrieve ID of the listing.")
        }
        
        
        var request = URLRequest(url:callURL!)
        request.httpMethod = "POST"
        
        let postString = "id=\(id)"
        request.httpBody = postString.data(using: String.Encoding.utf8)
        
        let task = URLSession.shared.dataTask(with: request as URLRequest) { (data, response, error) in
            if error != nil {
                print("ERROR")
            }
            
            do {
                //converting response to NSDictionary
                var contactJSON: NSDictionary!
                contactJSON =  try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as? NSDictionary
                
                //getting the JSON array contacts from the response
                let contacts: NSArray = contactJSON["offers"] as! NSArray
                
                //looping through all the json objects in the array contacts
                for i in 0 ..< contacts.count{
                    
                    //getting the data at each index
                    let contact:String = contacts[i] as! String
                    if self.myOffers.contains(contact) {
                        print("contact already made offer")
                    } else {
                        self.myOffers.append(contact)
                    }
                    print(contact)
                }
                //reload tableView
                DispatchQueue.main.async {
                    self.tableView.reloadData()
                }
                
            } catch {
                print(error)
            }
        }
        task.resume()
        
        
        //########################################################################################################################################################################################################
        
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        //return 4
        return myOffers.count
    }
    

    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = UITableViewCell()
        
        //Give the cell a text label and populate it with a name
        cell.textLabel?.font = UIFont.systemFont(ofSize: 20.0)
        cell.textLabel?.text = myOffers[indexPath.row]
        
        cell.backgroundColor = UIColor(red: 1.0, green: 1.0, blue: 1.0, alpha: 0.5)
        
        
        return cell
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    //set the height for all the cells
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 60
    }
    
    //Set the accept and decline offer sliding buttons
    func tableView(_ tableView: UITableView, editActionsForRowAt indexPath: IndexPath) -> [UITableViewRowAction]? {
        let accept = UITableViewRowAction(style: .normal, title: "Accept") { action, index in
            self.acceptOffer(index: indexPath.row)
        }
        accept.backgroundColor = UIColor(red: 0.33, green: 0.85, blue: 0.5, alpha: 1.0)
        
        let decline = UITableViewRowAction(style: .normal, title: "Decline") { action, index in
            self.declineOffer(index: indexPath.row)
        }
        decline.backgroundColor = .red
        
        
        return [decline, accept]
    }
    
    func acceptOffer(index: Int) {
        print("accept button tapped")
        let username = myOffers[index]
        print(username)
        
//        var request = URLRequest(url:acceptURL!)
//        request.httpMethod = "POST"
//        
//        // username is name of offerer id is id of listing
//        let postString = "username=\(username)&id=\(id)"
//        request.httpBody = postString.data(using: String.Encoding.utf8)
//        
//        let task = URLSession.shared.dataTask(with: request as URLRequest) { (data, response, error) in
//            if error != nil {
//                print("ERROR")
//            }
//            
//            do {
//                
//            } catch {
//                print(error)
//            }
//        }
//        task.resume()
//        
    }
    
    func declineOffer(index: Int) {
        print("decline button tapped")
        let username = myOffers[index]
        print(username)
    }
    
    func tableView(_ tableView: UITableView, canEditRowAt indexPath: IndexPath) -> Bool {
        return true
    }
    
    
//    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
//        //Set variables to pass to IndividualListingViewController
//        
//    }
    
    
//    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
//        if (segue.identifier == "myListingSegue") {
//            //Create an instance of the NavigationController
//            let navVC = segue.destination as? UINavigationController
//            //Create an instance of the destination IndividualListingViewController
//            let listingVC = navVC?.viewControllers.first as! IndividualListingViewController
//            
//            //give the variables in the destination values from the current viewcontroller
//            listingVC.lTitle = selectedTitle
//            listingVC.image = selectedImage
//            listingVC.descr = selectedDescr
//            listingVC.owner = selectedOwner
//            listingVC.price = selectedPrice
//            listingVC.btnText = "Rent Now - " + selectedPrice
//            
//        }
//    }
    
    
    
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
