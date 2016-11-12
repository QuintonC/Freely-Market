//
//  LoginViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 10/19/16.
//  Copyright © 2016 Freely Creative. All rights reserved.
//

import UIKit

class LoginViewController: UIViewController, UITextFieldDelegate {

    @IBOutlet weak var username: UITextField!
    @IBOutlet weak var password: UITextField!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(RegisterViewController.dismissKeyboard))
        
        view.addGestureRecognizer(tap)
        
    }
    
    // Changes which textfield is first responder
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        if textField == username {
            password.becomeFirstResponder()
        } else {
            loginTapped(self)
        }
        return true
    }
    
    
    func dismissKeyboard() {
        view.endEditing(true)
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    @IBAction func loginTapped(_ sender: AnyObject) {
        let username:NSString = self.username.text! as NSString
        let password:NSString = self.password.text! as NSString
        let defaults = UserDefaults.standard
        let usernameV = defaults.string(forKey: "username")
        let passwordV = defaults.string(forKey: "password")
        
        // Authentication
        
        
        if usernameV == nil || passwordV == nil {
            
            let alertController = UIAlertController(title: "Oops!", message: "It appears you have not registered, please register first and then proceed to logging in.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                self.performSegue(withIdentifier: "registerRedirect", sender: self)
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else if (username.isEqual(to: "") || password.isEqual(to: "")) {
            
            let alertController = UIAlertController(title: "Oops!", message: "Please be sure to enter login information before attempting to login.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert Dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else if (username.isEqual(to: usernameV!) && password.isEqual(to: passwordV!)) {
         
            self.performSegue(withIdentifier: "loginSuccess", sender: self)

        } else {
            
            let alertController = UIAlertController(title: "Oops!", message: "Password and Username pair not found.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert Dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
        }
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
