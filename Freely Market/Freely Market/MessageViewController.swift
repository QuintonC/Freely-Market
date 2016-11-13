//
//  MessageViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 11/13/16.
//  Copyright © 2016 Freely Creative. All rights reserved.
//

import UIKit

class MessageViewController: UIViewController {

    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var subjectTitle: UITextField!
    @IBOutlet weak var messageBody: UITextView!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(RegisterViewController.dismissKeyboard))
        view.addGestureRecognizer(tap)
        
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        self.messageBody.layer.cornerRadius = 5.0
        messageBody.text = "Placeholder"
        messageBody.textColor = UIColor.lightGray
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    func textViewDidBeginEditing(textView: UITextView) {
        if messageBody.textColor == UIColor.lightGray {
            messageBody.text = nil
            messageBody.textColor = UIColor.black
        }
    }
    
    func textViewDidEndEditing(textView: UITextView) {
        if messageBody.text.isEmpty {
            messageBody.text = "Placeholder"
            messageBody.textColor = UIColor.lightGray
        }
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
