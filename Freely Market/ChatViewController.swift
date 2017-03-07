//
//  ChatViewController.swift
//  Freely Market
//
//  Created by Austin Mitts on 1/30/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import Foundation
import UIKit
import WebKit


class ChatViewController: UIViewController, UITableViewDataSource, UITableViewDelegate {
    
    
    @IBOutlet weak var tableView: UITableView!

    
    
    var values:NSArray = []
    var NAMES = [String]()
    var user = "oklightning"
    
    
    
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        //get()
        
    }


    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    
    func getContacts() {
        var myContacts = [String]()
        let myURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getContacts.php")
        let request = NSMutableURLRequest(url: myURL! as URL)
        request.httpMethod = "POST"
        
        let postString = "user=\(user)"
        request.httpBody = postString.data(using: String.Encoding.utf8)
        
        let task = URLSession.shared.dataTask(with: myURL!) { (data, response, error) in
            if error != nil {
                print("ERROR")
            }
            
            do {
                //converting response to NSDictionary
                var contactJSON: NSDictionary!
                contactJSON =  try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as? NSDictionary
                
                //getting the JSON array contacts from the response
                let contacts: NSArray = contactJSON["contacts"] as! NSArray
                
                //looping through all the json objects in the array contacts
                for i in 0 ..< contacts.count{
                    
                    //getting the data at each index
                    let contact:String = contacts[i] as! String
                    
                    
                    //adding the data to the return array
                    print(contact)
                    myContacts.append(contact)
                    
                }
                
            } catch {
                print(error)
            }
        }
        task.resume()
        NAMES = myContacts
    }
    
    
    
//    @IBAction func send(_ sender: Any) {
//        let message = txtMessage.text
//        txtMessage.text = ""
//        var temp = txtDisplay.text
//        temp = temp! + "\n"+message!
//        txtDisplay.text = temp
//    }
    
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        
        getContacts()
        
        
        let cell = tableView.dequeueReusableCell(withIdentifier: "cell", for: indexPath) as! ContactCell
        
        
        cell.name.text = NAMES[indexPath.row]
        
        return cell
    }
    
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return NAMES.count
    }
    
}
