//
//  MessageViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 11/13/16.
//  Copyright © 2016 Freely Creative. All rights reserved.
//

import UIKit
import MessageUI

class MessageViewController: UIViewController, UITextViewDelegate, MFMailComposeViewControllerDelegate {

    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var subjectTitle: UITextField!
    @IBOutlet weak var messageBody: UITextView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        messageBody.delegate = self
        
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(MessageViewController.dismissKeyboard))
        view.addGestureRecognizer(tap)
        
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        self.messageBody.layer.cornerRadius = 5.0
        messageBody.text = "What can we help you with?"
        messageBody.textColor = UIColor.lightGray
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    func textViewDidBeginEditing(_ textView: UITextView) {
        if messageBody.textColor == UIColor.lightGray {
            messageBody.text = nil
            messageBody.textColor = UIColor.black
        }
    }
    
    func textViewDidEndEditing(_ textView: UITextView) {
        if messageBody.text.isEmpty {
            messageBody.text = "What can we help you with?"
            messageBody.textColor = UIColor.lightGray
        }
    }

    @IBAction func sendMessage(_ sender: Any) {
        let mailComposeViewController = configuredMailComposeViewController()
        if MFMailComposeViewController.canSendMail() {
            self.present(mailComposeViewController, animated: true, completion: nil)
        } else {
            self.showSendMailErrorAlert()
        }
    }
    
    func configuredMailComposeViewController() -> MFMailComposeViewController {
        let subj:String = subjectTitle.text!
        let body:String = messageBody.text
        let mailComposerVC = MFMailComposeViewController()
        mailComposerVC.mailComposeDelegate = self
        
        mailComposerVC.setToRecipients(["quintonchester@gmail.com"])
        mailComposerVC.setSubject(subj)
        mailComposerVC.setMessageBody(body, isHTML: false)
        
        return mailComposerVC
    }
    
    func showSendMailErrorAlert() {
        let alertController = UIAlertController(title: "Error", message: "Your email could not be sent, check your devices internet connectivity and please try again.", preferredStyle: .alert)
        let OKAction = UIAlertAction(title: "OK", style: .default) {
            (action:UIAlertAction) in
            print("Alert Dismissed")
        }
        alertController.addAction(OKAction)
        self.present(alertController, animated: true, completion:nil)

    }
    
    func mailComposeController(_ controller: MFMailComposeViewController, didFinishWith result: MFMailComposeResult, error: Error?) {
        controller.dismiss(animated: true, completion: nil)
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
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
