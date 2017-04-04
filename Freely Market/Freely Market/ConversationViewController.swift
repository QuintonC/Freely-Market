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

var CONTACT = String()

class ConversationViewController: UIViewController, UITableViewDataSource, UITableViewDelegate, UITextFieldDelegate {

    
    var contact = String()
    var conversation: [[String]] = []
    
    @IBOutlet var messageTextField: UITextField!
    @IBOutlet var tableView: UITableView!
    @IBOutlet var scrollView1: UIScrollView!
    @IBOutlet var scrollView2: UIScrollView!
    
//    var conversation = [["s","Hey"],["r","Hello"],["s","Whats up"],["r","oh not much you?"],["s","Nothing really, just thinking about going to the movies tonight"],["r","That sounds like fun"],["s","Yeah, youre welcom to join if you want to"],["r","I might take you up on that offer"],["s","coolio, just let me know"],["s","Hey"],["r","Hello"],["s","Whats up"],["r","oh not much you?"],["s","Nothing really, just thinking about going to the movies tonight"],["r","That sounds like fun"],["s","Yeah, youre welcom to join if you want to"],["r","I might take you up on that offer"],["s","coolio, just let me know"]]
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        self.title = contact
        CONTACT = contact
        
        
        //get conversation from database
        getMessages()
        

//        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(ConversationViewController.dismissKeyboard))
//        
//        if isEditing {
//            view.addGestureRecognizer(tap)
//        }        
    }
    
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        
    }

    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        textField.resignFirstResponder()
        return true
    }
    
    func textFieldDidBeginEditing(_ textField: UITextField) {
        scrollView1.setContentOffset(CGPoint(x: 0, y: 255), animated: true)
        scrollView2.setContentOffset(CGPoint(x: 0, y: 255), animated: true)
    }
    
    func textFieldDidEndEditing(_ textField: UITextField) {
        scrollView1.setContentOffset(CGPoint(x: 0, y: 0), animated: true)
        scrollView2.setContentOffset(CGPoint(x: 0, y: 0), animated: true)
    }
    
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let status = conversation[indexPath.row][0]
        let message = conversation[indexPath.row][1]

//        let cell = tableView.dequeueReusableCell(withIdentifier: "MessageCell", for: indexPath) as! MessageCell
        let cell = tableView.dequeueReusableCell(withIdentifier: "SenderCell", for: indexPath) as! SenderCell
        
//        cell.lblMessage.text = message
//        cell.lblName.text = status
        cell.sMessage.text = message
        cell.lblSender.text = status
        
        return cell
    }
    
    
    func tableView(_ conversationTableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return conversation.count
    }
    
    func getMessages() {
        conversation = []
        let myURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getConversation.php")
        var request = URLRequest(url:myURL!)
        request.httpMethod = "POST"
        
        let postString = "user1=\(USER)&user2=\(contact)"
        
        
        request.httpBody = postString.data(using: String.Encoding.utf8)
        
        let task = URLSession.shared.dataTask(with: request as URLRequest) { (data, response, error) in
            if error != nil {
                print("ERROR")
            }
            
            do {
                //converting response to NSDictionary
                var messageJSON: NSDictionary
                messageJSON = try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as! NSDictionary
                
                if let messages = messageJSON["messages"] as? [[String: AnyObject]] {
                    
                    for message in messages {
                        
                        if let name = message["sender"] as? String {
                            
                            if let messageText = message["message"] as? String {
                                print(name,messageText)
                                self.conversation.append([name, messageText])
                            }
                        }
                    }
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
    
    
    
    @IBAction func send(_ sender: Any) {
        let message:String = messageTextField.text!
        
        if message.characters.count > 64 {
            let myAlert = UIAlertController(title: "Alert", message:"Message cannot be longer than 64 characters", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
            }
            myAlert.addAction(OKAction)
            self.present(myAlert, animated: true, completion: nil)
        } else {
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
                                self.getMessages()
                            }
                        } else {
                            DispatchQueue.main.async {
                                let OKAction = UIAlertAction(title: "OK", style: .default) {
                                    (action:UIAlertAction) in
                                }
                                myAlert.addAction(OKAction)
                                self.present(myAlert, animated: true, completion: nil)
                            }
                        }
                    }
                    DispatchQueue.main.async {
                        self.tableView.reloadData()
                    }
                } catch let error as NSError {
                    print(err = error)
                }
            }
            task.resume()
            messageTextField.text = ""
            
            dismissKeyboard()
        }
    }
}






