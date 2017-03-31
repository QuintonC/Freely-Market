//
//  ConversationViewController.swift
//  Freely Market
//
//  Created by Austin Mitts on 3/29/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import Foundation
import UIKit
import WebKit


class ConversationViewController: UITableViewController, UITextFieldDelegate {

    
    
    @IBOutlet var txtFldMessage: UITextField!
    @IBOutlet var conversationTableView: UITableView!
    
    
    var contact = String()
    var conversation: [[String]] = []
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        self.title = contact
        
        tableView.estimatedRowHeight = 75
        tableView.rowHeight = UITableViewAutomaticDimension
        
        tableView.tableFooterView = UIView()
        
        //get conversation from database
        
        conversation = []
        let myURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getConversation.php")
        var request = URLRequest(url:myURL!)
        request.httpMethod = "POST"
        
//        let postString = "user1=\(USER)&user2=\(contact)"
        let postString = "user1=\("amitts")&user2=\("test1")"
        
        request.httpBody = postString.data(using: String.Encoding.utf8)
        
        let task = URLSession.shared.dataTask(with: request as URLRequest) { (data, response, error) in
            if error != nil {
                print("ERROR")
            }
            
            do {
                //converting response to NSDictionary
                var messageJSON: NSArray
                messageJSON = try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as! NSArray
                
                //getting the JSON array messages from the response
                let messages: NSArray = messageJSON[0] as! NSArray
                
                //looping through all the json objects in the array contacts
                for i in 0 ..< messages.count{
                    
                    //getting the data at each index
                    let message = messages[i] as! NSArray
                    
                    let messageSender:String = message[0] as! String
                    
                    
                    //getting the data at each index
                    let messageText:String = message[1] as! String
                    
                    
                    print(messageText)
                    print(messageSender)
                    //conversation.append(message + "\n")
                    self.conversation.append([messageSender, messageText])
                    
                    
                }
                //self.txtDisplay.text = conversation
                //reload tableView
                DispatchQueue.main.async {
                    self.tableView.reloadData()
                }
                
            } catch {
                print(error)
            }
        }
        task.resume()
        
        
//        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(ConversationViewController.dismissKeyboard))
//        
//        if isEditing {
//            view.addGestureRecognizer(tap)
//        }
        
    }
    
    
//    func dismissKeyboard() {
//        view.endEditing(true)
//    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        
    }

    override func numberOfSections(in tableView: UITableView) -> Int {
        // #warning Incomplete implementation, return the number of sections
        return 1
    }
    
    override func tableView(_ conversationTableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var status = String()
        var message = String()

        if indexPath.row < conversation.count {
            status = conversation[indexPath.row][0]
            message = conversation[indexPath.row][1]
        } else {
            let cell = tableView.dequeueReusableCell(withIdentifier: "newMessage")
            cell?.contentView.isUserInteractionEnabled = false
            return cell!
        }
        
        if status == USER {
            let cell = tableView.dequeueReusableCell(withIdentifier: "SenderMessageCell", for: indexPath) as! SenderMessageCell
            cell.lblMessage.text = message
            return cell
        } else {
            let cell = tableView.dequeueReusableCell(withIdentifier: "MessageCell", for: indexPath) as! MessageCell
            cell.lblMessage.text = message
            return cell
        }
    }
    
    
    override func tableView(_ conversationTableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return conversation.count + 1
    }
    
    
    @IBAction func send(_ sender: Any) {
        let message:String = txtFldMessage.text!
        
        let myURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/createMessage.php")
        var request = URLRequest(url:myURL!)
        request.httpMethod = "POST"
        
        let postString = "sender=\(USER)&reciever=\(contact)&message=\(message)"
        
        request.httpBody = postString.data(using: String.Encoding.utf8)
        
        let task = URLSession.shared.dataTask(with: request as URLRequest) {
            (data, response, error) in
            
            if error != nil {
                print("error is \(error)")
                return
            }
            
            var err: NSError?
            
            do {
                let json = try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as? NSDictionary
                
                
                if let parseJSON = json {
                    
                    let messageToDisplay:String = parseJSON["message"] as! String
                    let myAlert = UIAlertController(title: "Alert", message:messageToDisplay, preferredStyle: .alert)
                    
                    if messageToDisplay == "Could not send message" {
                        DispatchQueue.main.async {
                            let OKAction = UIAlertAction(title: "OK", style: .default) {
                                (action:UIAlertAction) in
                            }
                            myAlert.addAction(OKAction)
                            self.present(myAlert, animated: true, completion: nil)
                        }
                    } else if messageToDisplay == "Message successfully sent" {
                        DispatchQueue.main.async {
                            let OKAction = UIAlertAction(title: "OK", style: .default) {
                                (action:UIAlertAction) in
                                self.performSegue(withIdentifier: "registerSuccess", sender: self)
                            }
                            myAlert.addAction(OKAction)
                            self.present(myAlert, animated: true, completion: nil)
                        }
                    } else { //User has successfully registered
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
    }


}






