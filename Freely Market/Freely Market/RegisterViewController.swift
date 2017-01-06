//
//  RegisterViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 10/19/16.
//  Copyright © 2016 Freely Creative. All rights reserved.
//

import UIKit

class RegisterViewController: UIViewController, UITextFieldDelegate {

    @IBOutlet weak var username: UITextField!
    @IBOutlet weak var password: UITextField!
    @IBOutlet weak var fname: UITextField!
    @IBOutlet weak var lname: UITextField!
    @IBOutlet weak var email: UITextField!
    @IBOutlet weak var phone: UITextField!
    @IBOutlet weak var scrollView: UIScrollView!
    var activeField: UITextField?
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        self.username.delegate = self
        self.password.delegate = self
        self.fname.delegate = self
        self.lname.delegate = self
        self.email.delegate = self
        self.phone.delegate = self
        
        // Do any additional setup after loading the view.
        
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(RegisterViewController.dismissKeyboard))
        
        view.addGestureRecognizer(tap)
        
    }
    
    // EDIT THE LINES BELOW THIS FOR SCROLLVIEW / CHANGING VIEW
    
    func registerForKeyboardNotifications(){
        //Adding notifies on keyboard appearing
        NotificationCenter.default.addObserver(self, selector: #selector(keyboardWasShown(notification:)), name: NSNotification.Name.UIKeyboardWillShow, object: nil)
        NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillBeHidden(notification:)), name: NSNotification.Name.UIKeyboardWillHide, object: nil)
    }
    
    func deregisterFromKeyboardNotifications(){
        //Removing notifies on keyboard appearing
        NotificationCenter.default.removeObserver(self, name: NSNotification.Name.UIKeyboardWillShow, object: nil)
        NotificationCenter.default.removeObserver(self, name: NSNotification.Name.UIKeyboardWillHide, object: nil)
    }
    
    func keyboardWasShown(notification: NSNotification){
        //Need to calculate keyboard exact size due to Apple suggestions
        self.scrollView.isScrollEnabled = true
        var info = notification.userInfo!
        let keyboardSize = (info[UIKeyboardFrameBeginUserInfoKey] as? NSValue)?.cgRectValue.size
        let contentInsets : UIEdgeInsets = UIEdgeInsetsMake(0.0, 0.0, keyboardSize!.height, 0.0)
        
        self.scrollView.contentInset = contentInsets
        self.scrollView.scrollIndicatorInsets = contentInsets
        
        var aRect : CGRect = self.view.frame
        aRect.size.height -= keyboardSize!.height
        if let activeField = self.activeField {
            if (!aRect.contains(activeField.frame.origin)){
                self.scrollView.scrollRectToVisible(activeField.frame, animated: true)
            }
        }
    }
    
    func keyboardWillBeHidden(notification: NSNotification){
        //Once keyboard disappears, restore original positions
        var info = notification.userInfo!
        let keyboardSize = (info[UIKeyboardFrameBeginUserInfoKey] as? NSValue)?.cgRectValue.size
        let contentInsets : UIEdgeInsets = UIEdgeInsetsMake(0.0, 0.0, -keyboardSize!.height, 0.0)
        self.scrollView.contentInset = contentInsets
        self.scrollView.scrollIndicatorInsets = contentInsets
        self.view.endEditing(true)
        self.scrollView.isScrollEnabled = false
    }
    
    func textFieldDidBeginEditing(_ textField: UITextField){
        activeField = textField
    }
    
    func textFieldDidEndEditing(_ textField: UITextField){
        activeField = nil
    }
    
    // EDIT THE LINES ABOVE THIS
    
    
    // Changes which textfield is first responder
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        if textField == username {
            password.becomeFirstResponder()
        } else if textField == password {
            fname.becomeFirstResponder()
        } else if textField == fname {
            lname.becomeFirstResponder()
        } else if textField == lname {
            email.becomeFirstResponder()
        } else if textField == email {
            phone.becomeFirstResponder()
        } else if textField == phone {
            registerTapped(self)
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
    
    
    @IBAction func registerTapped(_ sender: AnyObject) {
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
            

            /* let defaults = UserDefaults.standard
            defaults.set(username, forKey: "username")
            defaults.set(password, forKey: "password")
            defaults.set(fname, forKey: "firstName")
            defaults.set(lname, forKey: "lastName")
            defaults.set(email, forKey: "email")
            defaults.set(phone, forKey: "phone")
            print(UserDefaults.standard.dictionaryRepresentation()); */
            

            // database registration here
            let myURL = URL(string: "http://cgi.soic.indiana.edu/~team12/register.php")
            var request = URLRequest(url:myURL!)
            request.httpMethod = "POST"
            
            let postString = "username=\(username)&password=\(password)"
            request.httpBody = postString.data(using: String.Encoding.utf8)
            
            let task = URLSession.shared.dataTask(with: request as URLRequest) {
                (data, response, error) in
                
                if error != nil {
                    print("error=\(error)")
                    return
                }
            
                var err: NSError?
                
                do {
                    let json = try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as? NSDictionary
            
                
                    if let parseJSON = json {
                        let resultValue = parseJSON["status"] as! String
                        print("result: \(resultValue)")
                        
                        var isUserRegisterd:Bool = false
                        if (resultValue == "Success") {
                            isUserRegisterd = true
                        }
                        
                        var messageToDisplay:String = parseJSON["message"] as! String
                        if (!isUserRegisterd) {
                            messageToDisplay = parseJSON["message"] as! String
                        }
                        
                        DispatchQueue.main.async {
                            let myAlert = UIAlertController(title: "Alert", message:messageToDisplay, preferredStyle: .alert)
                            let OKAction = UIAlertAction(title: "OK", style: .default) {
                                (action:UIAlertAction) in
                                self.performSegue(withIdentifier: "registerSuccess", sender: self)
                            }
                            myAlert.addAction(OKAction)
                            self.present(myAlert, animated: true, completion: nil)
                        }
                    }
                } catch let error as NSError {
                    err = error
                }
            }
            
            task.resume()
            
            
            
            /* let alertController = UIAlertController(title: "Success", message: "You've successfully registered for Freely Market. Press OK to go to the login screen.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                self.performSegue(withIdentifier: "registerSuccess", sender: self)
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil) */
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
