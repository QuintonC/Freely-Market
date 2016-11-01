//
//  RegisterViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 10/19/16.
//  Copyright © 2016 Freely Creative. All rights reserved.
//

import UIKit

class RegisterViewController: UIViewController {

    @IBOutlet weak var username: UITextField!
    @IBOutlet weak var password: UITextField!
    @IBOutlet weak var fname: UITextField!
    @IBOutlet weak var lname: UITextField!
    @IBOutlet weak var email: UITextField!
    @IBOutlet weak var phone: UITextField!
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // Do any additional setup after loading the view.
        
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(RegisterViewController.dismissKeyboard))
        
        view.addGestureRecognizer(tap)
        
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    func isValidEmail(email:String) -> Bool {
        let emailRegEx = "^[_A-Za-z0-9-]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$"
        let emailTest = NSPredicate(format:"SELF MATCHES %@", emailRegEx)
        return emailTest.evaluate(with: email)
    }
    
    func isValidUsername(username:String) -> Bool {
        let usernameRegex = "^[a-z0-9_-]{3,20}$"
        let usernameTest = NSPredicate(format: "SELF MATCHES %@", usernameRegex)
        return usernameTest.evaluate(with: username)
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
    
    
    @IBAction func registerTapped(_ sender: UIButton) {
        let username:NSString = self.username.text! as NSString
        let password:NSString = self.password.text! as NSString
        let fname:NSString = self.fname.text! as NSString
        let lname:NSString = self.lname.text! as NSString
        let email:NSString = self.email.text! as NSString
        let phone:NSString = self.phone.text! as NSString
        
        if (username.isEqual(to: " ") || password.isEqual(to: " ")) {
            
            let alertController = UIAlertController(title: "Oops!", message: "Double check your inputs.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                    print("Alert Dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else if (username.isEqual(to: "") || password.isEqual(to: "") || fname.isEqual(to: "") || lname.isEqual(to: "") || email.isEqual(to: "") || phone.isEqual(to: "")) {
            
            let alertController = UIAlertController(title: "Oops!", message: "It looks like you left a field blank, double check your inputs.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else if (isValidEmail(email: email as String) == false) {
            
            let alertController = UIAlertController(title: "Oops!", message: "It appears that you've entered an incorrect email address", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else if (isValidUsername(username: username as String) == false) {
            
            let alertController = UIAlertController(title: "Oops!", message: "It appears that you've entered an incorrect username. Acceptable characters: a-z, 0-9, _, -, and must be between 3-20 characters.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else if (isValidPassword(password: password as String) == false) {
            
            let alertController = UIAlertController(title: "Oops!", message: "It appears that you've entered an incorrect password. Password must contain at least one letter, at least one number, and be at least 7 characters and no more than 20.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else if (isValidPhone(phone: phone as String) == false) {
            
            let alertController = UIAlertController(title: "Oops!", message: "It appears that you've entered an incorrect phone number. Phone number should be structure as: xxx-xxx-xxxx", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else if (isValidName(name: fname as String) == false) ||  (isValidName(name: lname as String) == false) {
            
            let alertController = UIAlertController(title: "Oops!", message: "It appears that you've entered an incorrect first name or last name, please double check your inputs.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else {
            let defaults = UserDefaults.standard
            defaults.set(username, forKey: "username")
            defaults.set(password, forKey: "password")
            defaults.set(fname, forKey: "firstName")
            defaults.set(lname, forKey: "lastName")
            defaults.set(email, forKey: "email")
            defaults.set(phone, forKey: "phone")
            print(UserDefaults.standard.dictionaryRepresentation());
            
            let alertController = UIAlertController(title: "Success", message: "You've successfully registered for Freely Market. Press OK to go to the login screen.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                self.performSegue(withIdentifier: "registerSuccess", sender: self)
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
