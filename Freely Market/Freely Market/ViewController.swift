//
//  ViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 10/19/16.
//  Copyright © 2016 Freely Creative. All rights reserved.
//

import UIKit

class ViewController: UIViewController, UITextFieldDelegate {
    
    
    @IBOutlet weak var phone: UITextField!
    @IBOutlet weak var email: UITextField!
    @IBOutlet weak var lname: UITextField!
    @IBOutlet weak var fname: UITextField!
    @IBOutlet weak var password: UITextField!
    @IBOutlet weak var menuButton: UIBarButtonItem!
    let defaults = UserDefaults.standard
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(RegisterViewController.dismissKeyboard))
        view.addGestureRecognizer(tap)
        
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
//    func populateFields(_: UITextField) {
//        if defaults.string(forKey: "phone") != nil {
//            phone.text = defaults.string(forKey: "phone")
//        } else {
//            phone.text = ""
//        }
//        
//        if defaults.string(forKey: "fname") != nil {
//            fname.text = defaults.string(forKey: "fname")
//        } else {
//            fname.text = ""
//        }
//    }
    
    func isValidEmail(email:String) -> Bool {
        let emailRegEx = "^[_A-Za-z0-9-]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$"
        let emailTest = NSPredicate(format:"SELF MATCHES %@", emailRegEx)
        return emailTest.evaluate(with: email)
    }
    
    func isValidPassword(password:String) -> Bool {
        let passwordRegex = "^(?=.*\\d)(?=.*[a-z]).{7,20}$"
        let passwordTest = NSPredicate(format: "SELF MATCHES %@", passwordRegex)
        return passwordTest.evaluate(with: password)
    }
    
    func isValidPhone(phone:String) -> Bool {
        let phoneRegex = "^[2-9]\\d{2}-\\d{3}-\\d{4}$"
        let phoneTest = NSPredicate(format: "SELF MATCHES %@", phoneRegex)
        return phoneTest.evaluate(with: phone)
    }
    
    func isValidName(name:String) -> Bool {
        let nameRegex = "^[a-zA-Z]+(([\\'\\,\\.\\-][a-zA-Z])?[a-zA-Z]*)*$"
        let nameTest = NSPredicate(format: "SELF MATCHES %@", nameRegex)
        return nameTest.evaluate(with: name)
    }


    @IBAction func updateInfo(_ sender: AnyObject) {
        let password:NSString = self.password.text! as NSString
        let fname:NSString = self.fname.text! as NSString
        let lname:NSString = self.lname.text! as NSString
        let email:NSString = self.email.text! as NSString
        let phone:NSString = self.phone.text! as NSString
        
        
        if (isValidEmail(email: email as String) == false) {
            
            let alertController = UIAlertController(title: "Oops!", message: "You've entered an incorrect email address", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)

        } else if (isValidPassword(password: password as String) == false) {
            
            let alertController = UIAlertController(title: "Oops!", message: "You've entered an incorrect password. Password must contain at least one letter, at least one number, and be at least 7 characters and no more than 20.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else if (isValidPhone(phone: phone as String) == false) {
            
            let alertController = UIAlertController(title: "Oops!", message: "You've entered an incorrect phone number. Phone number should be structure as: xxx-xxx-xxxx", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else if (isValidName(name: fname as String) == false) ||  (isValidName(name: lname as String) == false) {
            
            let alertController = UIAlertController(title: "Oops!", message: "You've entered an incorrect first name or last name, please double check your inputs.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        }
        else {
            //remove old app data
            defaults.removeObject(forKey: "password")
            defaults.removeObject(forKey: "firstName")
            defaults.removeObject(forKey: "lastName")
            defaults.removeObject(forKey: "email")
            defaults.removeObject(forKey: "phone")
            
            // Set New Data
            defaults.set(password, forKey: "password")
            defaults.set(fname, forKey: "firstName")
            defaults.set(lname, forKey: "lastName")
            defaults.set(email, forKey: "email")
            defaults.set(phone, forKey: "phone")
            defaults.synchronize()
            print(UserDefaults.standard.dictionaryRepresentation())
            
            let alertController = UIAlertController(title: "Success", message: "You've successfully edited your information for Freely Market.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert Dismissed")
                self.performSegue(withIdentifier: "editSuccess", sender: self)
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
        }
        
    
    }
    
    @IBAction func deleteAccount(_ sender: UIButton) {
        
        let alertController = UIAlertController(title: "Are you sure?", message: "Your account will be deleted permanently.", preferredStyle: .alert)
        let cancelAction = UIAlertAction(title: "Cancel", style: .cancel) {
            (action:UIAlertAction) in
            print("Delete Account Cancelled")
        }
        let OKAction = UIAlertAction(title: "OK", style: .default) {
            (action:UIAlertAction) in
            print("Account Deleted")
            
            //delete account data
            self.defaults.removeObject(forKey: "username")
            self.defaults.removeObject(forKey: "password")
            self.defaults.removeObject(forKey: "firstName")
            self.defaults.removeObject(forKey: "lastName")
            self.defaults.removeObject(forKey: "phone")
            self.defaults.removeObject(forKey: "email")
            self.logout(self)
        }
        alertController.addAction(OKAction)
        alertController.addAction(cancelAction)
        self.present(alertController, animated: true, completion:nil)
    }
    
    @IBAction func logout(_ sender: AnyObject) {
        
        let alertController = UIAlertController(title: "Success", message: "You have been logged out.", preferredStyle: .alert)
        let OKAction = UIAlertAction(title: "OK", style: .default) {
            (action:UIAlertAction) in
            self.performSegue(withIdentifier: "logoutSegue", sender: self)
        }
        alertController.addAction(OKAction)
        self.present(alertController, animated: true, completion:nil)
        
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }


}
