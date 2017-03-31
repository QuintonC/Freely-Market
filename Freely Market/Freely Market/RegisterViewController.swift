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
    @IBOutlet weak var password2: UITextField!
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
        self.password2.delegate = self
        self.fname.delegate = self
        self.lname.delegate = self
        self.email.delegate = self
        self.phone.delegate = self
        
        // Do any additional setup after loading the view.
        
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(RegisterViewController.dismissKeyboard))
        
        NotificationCenter.default.addObserver(self, selector: #selector(keyboardUp), name: NSNotification.Name.UIKeyboardWillShow, object: nil)
        NotificationCenter.default.addObserver(self, selector: #selector(keyboardDown), name: NSNotification.Name.UIKeyboardWillHide, object: nil)
        
        view.addGestureRecognizer(tap)
        
    }
    
    // KEYBOARD VIEW EDITING START
    
    func textFieldDidBeginEditing(_ textField: UITextField){
        activeField = textField
    }
    
    func textFieldDidEndEditing(_ textField: UITextField){
        activeField = nil
    }
    
    func keyboardUp(notification: NSNotification) {
        let absoluteFrame: CGRect = (activeField?.convert(activeField!.frame, to: UIApplication.shared.keyWindow))!
        let keyboardSize = (notification.userInfo?[UIKeyboardFrameBeginUserInfoKey] as? NSValue)?.cgRectValue
        
        
        if self.activeField != nil {
            print(absoluteFrame.origin.y)
            print((keyboardSize?.height)!)
            //if (absoluteFrame.origin.y < (keyboardSize?.height)!) {
            if (activeField == username || activeField == password || activeField == password2) {
                self.view.frame.origin.y = 0
            } else {
                self.view.frame.origin.y = -(keyboardSize?.height)!
            }
        }
    }
    
    func keyboardDown(notification: NSNotification) {
        if let keyboardSize = (notification.userInfo?[UIKeyboardFrameBeginUserInfoKey] as? NSValue)?.cgRectValue {
            self.view.frame.origin.y = 0
        }
    }
    
    // END KEYBOARD VIEW SHIFTING
    
    // Changes which textfield is first responder
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        if textField == username {
            password.becomeFirstResponder()
        } else if textField == password {
            password2.becomeFirstResponder()
        } else if textField == password2 {
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
    
    func matchingPasswords(password:String, password2:String) -> Bool {
        return password == password2
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
        let password2:NSString = self.password2.text! as NSString
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
            
        } else if (matchingPasswords(password: password as String, password2: password2 as String) == false) {
            
            let alertController = UIAlertController(title: "Oops!", message: "It appears that your passwords do not match. Please try again.", preferredStyle: .alert)
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
            
            // database registration here
            let myURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/createUser.php")
            var request = URLRequest(url:myURL!)
            request.httpMethod = "POST"
            
            let postString = "username=\(username)&password=\(password)&fname=\(fname)&lname=\(lname)&email=\(email)&phone=\(phone)"
            
            request.httpBody = postString.data(using: String.Encoding.utf8)
            
            let task = URLSession.shared.dataTask(with: request as URLRequest) {
                (data, response, error) in
                
                if error != nil {
                    print("error is \(String(describing: error))")
                    return
                }
            
                var err: NSError?
                
                do {
                    let json = try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as? NSDictionary
            
                
                    if let parseJSON = json {
                        
                        let messageToDisplay:String = parseJSON["message"] as! String
                        let myAlert = UIAlertController(title: "Alert", message:messageToDisplay, preferredStyle: .alert)
                        
                        if messageToDisplay == "User already exists" {
                            DispatchQueue.main.async {
                                let OKAction = UIAlertAction(title: "OK", style: .default) {
                                    (action:UIAlertAction) in
                                }
                                myAlert.addAction(OKAction)
                                self.present(myAlert, animated: true, completion: nil)
                            }
                        } else if messageToDisplay == "User created successfully" {
                            DispatchQueue.main.async {
                                let OKAction = UIAlertAction(title: "OK", style: .default) {
                                    (action:UIAlertAction) in
                                    self.performSegue(withIdentifier: "registerSuccess", sender: self)
                                }
                                myAlert.addAction(OKAction)
                                self.present(myAlert, animated: true, completion: nil)
                            }
                        } else { //User has successfully registered
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
