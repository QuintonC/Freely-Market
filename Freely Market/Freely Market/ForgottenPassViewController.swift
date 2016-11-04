//
//  ForgottenPassViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 11/3/16.
//  Copyright © 2016 Freely Creative. All rights reserved.
//

import UIKit

class ForgottenPassViewController: UIViewController {

    @IBOutlet weak var email: UITextField!
    @IBOutlet weak var phone: UITextField!
    @IBOutlet weak var password: UITextField!
    @IBOutlet weak var passwordVerify: UITextField!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(ForgottenPassViewController.dismissKeyboard))
        
        view.addGestureRecognizer(tap)
        
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    @IBAction func forgotPassPressed(_ sender: AnyObject) {
        let email:NSString = self.email.text! as NSString
        let phone:NSString = self.phone.text! as NSString
        let defaults = UserDefaults.standard
        let emailV = defaults.string(forKey: "email")
        let phoneV = defaults.string(forKey: "phone")
        
        // Verification
        
        if emailV == nil || phoneV == nil {
            
            let alertController = UIAlertController(title: "Oops!", message: "That email address and phone number combination were not found. Please try again, or press 'Register' to be taken to the registration screen.", preferredStyle: .alert)
            let cancelAction = UIAlertAction(title: "Try Again", style: .cancel) {
                (action:UIAlertAction) in
                print("Alert Dismissed")
            }
            let OKAction = UIAlertAction(title: "Register", style: .default) {
                (action:UIAlertAction) in
                self.performSegue(withIdentifier: "registerRedirect", sender: self)
            }
            alertController.addAction(OKAction)
            alertController.addAction(cancelAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else if (email.isEqual(to: "") && phone.isEqual(to: "")) {
            
            let alertController = UIAlertController(title: "Oops!", message: "Please be sure to enter information that helps us find your account.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert Dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else if (email.isEqual(to: emailV!) && phone.isEqual(to: phoneV!)) {
            
            self.performSegue(withIdentifier: "resetPassSegue", sender: self)
            
            
        } else if (
            (email.isEqual(to: emailV!) && !phone.isEqual(to: phoneV!)) ||
            (!email.isEqual(to: emailV!) && phone.isEqual(to: phoneV!))
            ) {
            
            let alertController = UIAlertController(title: "Oops!", message: "One of the fields contains incorrect information. Please try again.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert Dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else {
            let alertController = UIAlertController(title: "Oops!", message: "Please try again.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert Dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
        }
    }
    
    func isValidPassword(password:String) -> Bool {
        let passwordRegex = "^(?=.*\\d)(?=.*[a-z]).{7,20}$"
        let passwordTest = NSPredicate(format: "SELF MATCHES %@", passwordRegex)
        return passwordTest.evaluate(with: password)
    }
    
    @IBAction func savePassword(_ sender: AnyObject) {
        let password:NSString = self.password.text! as NSString
        let passwordVerify:NSString = self.passwordVerify.text! as NSString
        let defaults = UserDefaults.standard
        
        if (isValidPassword(password: password as String) == false) {
         
            let alertController = UIAlertController(title: "Oops!", message: "Please enter a valid password. Password guidelines are outlined above the text fields.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert Dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else if (password != passwordVerify) {
            
            let alertController = UIAlertController(title: "Oops!", message: "Both passwords must match.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert Dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
        
    
        } else if ((password == passwordVerify) && isValidPassword(password: password as String)) {
            //remove old password
            defaults.removeObject(forKey: "password")
            //set new password
            defaults.set(password, forKey: "password")
            print(UserDefaults.standard.dictionaryRepresentation())
            
            //redirect user after verifying password was changed
            let alertController = UIAlertController(title: "Success", message: "Your password has successfully been changed.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                self.performSegue(withIdentifier: "passwordChanged", sender: self)
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
        } else {
            
            let alertController = UIAlertController(title: "Oops!", message: "Something isn't quite right. Please try again.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                self.performSegue(withIdentifier: "passwordChanged", sender: self)
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
