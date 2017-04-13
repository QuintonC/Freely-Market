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
    var height = 255
    
    @IBOutlet var messageTextField: UITextField!
    @IBOutlet var tableView: UITableView!
    @IBOutlet var scrollView1: UIScrollView!
    @IBOutlet var scrollView: UIScrollView!
    @IBOutlet var sendBtn: UIButton!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        self.title = contact
        
        CONTACT = contact
        sendBtn.layer.cornerRadius = 5
        
        
        //Create nav bar
        let navigationBar = navigationController!.navigationBar
        
        //give nav bar a refresh button
        let rightButton = UIBarButtonItem(barButtonSystemItem: UIBarButtonSystemItem.refresh, target: self, action: #selector(ConversationViewController.refresh))
        navigationItem.rightBarButtonItem = rightButton
        
        //get conversation from database
        getMessages()
        

/////////////  Begin  //////////////////////   AUTO REFRESH   ///////////////////////////////////////////////////////////
//        var timer = Timer.scheduledTimer(timeInterval: 15, target: self, selector: #selector(ConversationViewController.getMessages), userInfo: nil, repeats: true)
        
//        var timer = Timer.scheduledTimer(timeInterval: 1.0, target: self, selector: Selector("reloadTableView"), userInfo: nil, repeats: true)
///////////////  End  ////////////////////   AUTO REFRESH   ///////////////////////////////////////////////////////////
    }
    
//    func reloadTableView() {
//        OperationQueue.main.addOperation {
//            self.getMessages()
//        }
//    }
    
    
    func refresh() {
        print("refreshing")
        viewDidLoad()
        
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
        autoScroll()
    }
    
    //func to scroll to bottom of table view to see most recent message
    func autoScroll() {
        let numberOfSections = self.tableView.numberOfSections
        let numberOfRows = self.tableView.numberOfRows(inSection: numberOfSections-1)
        if (numberOfRows > 0) {
            let indexPath = IndexPath(row: numberOfRows-1, section: (numberOfSections-1))
            self.tableView.scrollToRow(at: indexPath, at: .bottom, animated: true)
        }
    }
    
    func keyboardWillShow(notification: NSNotification) {
        if let keyboardSize = (notification.userInfo?[UIKeyboardFrameBeginUserInfoKey] as? NSValue)?.cgRectValue {
            let keyboardHeight = keyboardSize.height
            print(keyboardHeight)
            height = Int(keyboardHeight)
        }
    }

    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        textField.resignFirstResponder()
        return true
    }
    
    func textFieldDidBeginEditing(_ textField: UITextField) {
        NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillShow), name: .UIKeyboardWillShow, object: nil)
        
        scrollView1.setContentOffset(CGPoint(x: 0, y: height), animated: true)
        scrollView.setContentOffset(CGPoint(x: 0, y: height), animated: true)
    }
    
    func textFieldDidEndEditing(_ textField: UITextField) {
        scrollView1.setContentOffset(CGPoint(x: 0, y: 0), animated: true)
        scrollView.setContentOffset(CGPoint(x: 0, y: 0), animated: true)
    }
    
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let status = conversation[indexPath.row][0]
        let message = conversation[indexPath.row][1]
        let cell = tableView.dequeueReusableCell(withIdentifier: "SenderCell", for: indexPath) as! SenderCell
        cell.backgroundColor = UIColor.clear
        cell.contentView.backgroundColor = UIColor.clear
        let cellStyle : UIView = UIView(frame: CGRect(x: 8, y: 8, width: self.view.frame.size.width-10, height: 60))
        cellStyle.layer.backgroundColor = CGColor(colorSpace: CGColorSpaceCreateDeviceRGB(), components: [1.0, 1.0, 1.0, 0.7])
        cellStyle.layer.masksToBounds = false
        cellStyle.layer.cornerRadius = 5.0
        cellStyle.layer.shadowOffset = CGSize(width: 1, height: 1)
        cellStyle.layer.shadowOpacity = 0.5
        cell.contentView.addSubview(cellStyle)
        cell.contentView.sendSubview(toBack: cellStyle)
        cell.sMessage.text = message
        cell.lblSender.text = status
        return cell
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 85
    }
    
    
    func tableView(_ conversationTableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return conversation.count
    }
    
    //Function to get the conversation from the database
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
    
    //func to send message and add it to the database
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
                                self.refresh()
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






