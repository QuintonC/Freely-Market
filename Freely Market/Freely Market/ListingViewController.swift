//
//  ViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 10/19/16.
//  Copyright © 2016 Freely Creative. All rights reserved.
//

import UIKit

class ListingViewController: UIViewController, UITableViewDataSource, UITableViewDelegate {
    
    var values = NSArray()
    
    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var tableView: UITableView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(RegisterViewController.dismissKeyboard))
        view.addGestureRecognizer(tap)
        
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        let url = NSURL(string: "http://cgi.soic.indiana.edu/~team12/api/buyListings.php")
        let data = NSData(contentsOf: url! as URL)
        var err: NSError?
        do {
            values = try JSONSerialization.jsonObject(with: data! as Data, options: .mutableContainers) as! NSArray
        } catch let error as NSError {
            print(err = error)
        }
        tableView.reloadData()
        print(values)
        
        
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        print(values.count)
        return values.count
    }

    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        let cell = tableView.dequeueReusableCell(withIdentifier: "cell", for: indexPath) as! CellData
        
        let maindata = values[indexPath.row] as! [String:Any]
        
        cell.listingTitle.text = (maindata["title"] as AnyObject) as? String
        cell.listingPrice.text = (maindata["price"] as AnyObject) as? String
        cell.listingImage.image = (maindata["picture"] as AnyObject) as? UIImage
        
        return cell
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
