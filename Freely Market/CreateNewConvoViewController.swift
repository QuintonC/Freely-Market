//
//  CreateNewConvoViewController.swift
//  Freely Market
//
//  Created by Austin Mitts on 4/3/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import Foundation
import UIKit
import WebKit


class CreateNewConvoViewController: UIViewController, UITextFieldDelegate {
    
    
    var contact = String()
    var message = String()
    var height = 255
    
    
    var alert = String()
    
    var validUserName = Bool()
    
    
    @IBOutlet var usernameTextField: UITextField!
    @IBOutlet var messageTextField: UITextField!
    @IBOutlet var scrollView: UIScrollView!
    @IBOutlet var sendBtn: UIButton!
    
    
    //    var conversation = [["s","Hey"],["r","Hello"],["s","Whats up"],["r","oh not much you?"],["s","Nothing really, just thinking about going to the movies tonight"],["r","That sounds like fun"],["s","Yeah, youre welcom to join if you want to"],["r","I might take you up on that offer"],["s","coolio, just let me know"],["s","Hey"],["r","Hello"],["s","Whats up"],["r","oh not much you?"],["s","Nothing really, just thinking about going to the movies tonight"],["r","That sounds like fun"],["s","Yeah, youre welcom to join if you want to"],["r","I might take you up on that offer"],["s","coolio, just let me know"]]
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        
        sendBtn.layer.cornerRadius = 5

    }
    
    func keyboardWillShow(notification: NSNotification) {
        if let keyboardSize = (notification.userInfo?[UIKeyboardFrameBeginUserInfoKey] as? NSValue)?.cgRectValue {
            let keyboardHeight = keyboardSize.height
            //print(keyboardHeight)
            height = Int(keyboardHeight)
        }
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
    
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        textField.resignFirstResponder()
        return true
    }
    
    func textFieldDidBeginEditing(_ textField: UITextField) {
        NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillShow), name: .UIKeyboardWillShow, object: nil)
        
        scrollView.setContentOffset(CGPoint(x: 0, y: height), animated: true)
    }
    
    func textFieldDidEndEditing(_ textField: UITextField) {
        scrollView.setContentOffset(CGPoint(x: 0, y: 0), animated: true)
    }
    
    
    
    
    
    @IBAction func send(_ sender: Any) {
        contact = usernameTextField.text! as String
        message = messageTextField.text!
        
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
                            //self.getMessages()
                            self.performSegue(withIdentifier: "newMessage", sender: self)
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
            } catch let error as NSError {
                print(err = error)
            }
        }
        task.resume()
        messageTextField.text = ""
        usernameTextField.text = ""
        
    }
    
    
    
    
    
    
    override func shouldPerformSegue(withIdentifier identifier: String, sender: Any?) -> Bool {
        return false
    }
    
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if segue.identifier == "newMessage" {
            //Create instance of ConversationViewController
            let destinationVC = segue.destination as! ConversationViewController
            //Give ConversationViewController's variable contact a value
            destinationVC.contact = contact
        }
    }
    
}

