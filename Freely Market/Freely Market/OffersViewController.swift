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
    
    var Offers: [[String]] = []
    
    @IBOutlet weak var tableView: UITableView!
    @IBOutlet weak var menuButton: UIBarButtonItem!
    
    var offerBy = String()
    
    var type = String()
    var id = String()
    
    override func viewDidLoad() {
        
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        
        super.viewDidLoad()
        //getOffers()
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return 4
        //return Offers.count
    }
    
    //function to get offers on the listing
    func getOffers() {
        let requestURL: NSURL = NSURL(string: "http://cgi.soic.indiana.edu/~team12/api/getOffers.php")!
        let urlRequest: NSMutableURLRequest = NSMutableURLRequest(url: requestURL as URL)
        urlRequest.httpMethod = "POST"
        let postString = "owner=\(USER)&type=\(type)&id=\(id)"
        urlRequest.httpBody = postString.data(using: String.Encoding.utf8)
        
        let session = URLSession.shared
        let task = session.dataTask(with: urlRequest as URLRequest) {
            (data, response, error) -> Void in
            
            let httpResponse = response as! HTTPURLResponse
            let statusCode = httpResponse.statusCode
            if (statusCode == 200) {
                do {
                    let json = try JSONSerialization.jsonObject(with: data!, options: .allowFragments) as! [String:AnyObject]
                    
                    
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
        let cell = UITableViewCell()
        
        //Give the cell a text label and populate it with a name
        cell.textLabel?.font = UIFont.systemFont(ofSize: 20.0)
        cell.textLabel?.text = offerBy
        
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
    func tableView(_ tableView: UITableView, editActionsForRowAt: IndexPath) -> [UITableViewRowAction]? {
        let accept = UITableViewRowAction(style: .normal, title: "Accept") { action, index in
            print("accept button tapped")
        }
        accept.backgroundColor = UIColor(red: 0.33, green: 0.85, blue: 0.5, alpha: 1.0)
        
        let decline = UITableViewRowAction(style: .normal, title: "Decline") { action, index in
            print("decline button tapped")
        }
        decline.backgroundColor = .red
        
        
        return [decline, accept]
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
