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
    
    
    @IBOutlet var menuButton: UIBarButtonItem!
    
    //@IBOutlet weak var tableView: UITableView!

    @IBOutlet var tableView: UITableView!
    
    var names = [String]()
    var user = USER
    
    override func viewDidLoad() {
        super.viewDidLoad()

        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        tableView.dataSource = self
        tableView.delegate = self
        
        // Do any additional setup after loading the view, typically from a nib.
        //getContacts()
        
    }
    
    override func viewWillAppear(_ animated: Bool) {
        getContacts()
    }
    
    //function to get all of the usernames of people the user has had a conversation with
    func getContacts() {
        names = []
        var myContacts:[String] = []
        let myURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getContacts.php")
        var request = URLRequest(url:myURL!)
        request.httpMethod = "POST"
        
        let postString = "user=\(user)"
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
                let contacts: NSArray = contactJSON["contacts"] as! NSArray
                
                //looping through all the json objects in the array contacts
                for i in 0 ..< contacts.count{
                    
                    //getting the data at each index
                    let contact:String = contacts[i] as! String
                    
                    
                    //adding the data to the return array
                    //print(contact)
                    //myContacts.append(contact)
                    myContacts.append(contact)
                    self.names.append(contact)
                    //print(contact)
                    
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
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        //Version 1
        //let cell = tableView.dequeueReusableCell(withIdentifier: "cell", for: indexPath) as! ContactCell
        //cell.name.text = names[indexPath.row]
        
        //Version 2 (working)
        let cell = UITableViewCell()
        
        //Give the cell a text label and populate it with a name
        cell.textLabel?.font = UIFont.systemFont(ofSize: 20.0)
        cell.textLabel?.text = names[indexPath.row]
        
        //change the cells background color
        cell.backgroundColor = UIColor.clear
        cell.contentView.backgroundColor = UIColor.clear
        
        //more styling for the cell
        let cellStyle : UIView = UIView(frame: CGRect(x: 10, y: 8, width: self.view.frame.size.width - 20, height: 50))
        
        cellStyle.layer.backgroundColor = CGColor(colorSpace: CGColorSpaceCreateDeviceRGB(), components: [1.0, 1.0, 1.0, 0.8])
        cellStyle.layer.masksToBounds = false
        cellStyle.layer.cornerRadius = 5.0
        cellStyle.layer.shadowOffset = CGSize(width: 1, height: 1)
        cellStyle.layer.shadowOpacity = 0.5
        
        cell.contentView.addSubview(cellStyle)
        cell.contentView.sendSubview(toBack: cellStyle)
        
        return cell
    }
    
    //set the height for all the cells
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 60
    }
    
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return names.count
    }
    
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        // Perform segue showConversation with selected cell as sender
        performSegue(withIdentifier: "showConversation", sender: names[indexPath.row])
    }
    
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if segue.identifier == "showConversation" {
            //Create instance of ConversationViewController
            let destinationVC = segue.destination as! ConversationViewController
            //Give ConversationViewController's variable contact a value
            destinationVC.contact = sender as! String
        }
    }
}
