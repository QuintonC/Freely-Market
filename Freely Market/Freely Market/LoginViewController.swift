//
//  LoginViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 10/19/16.
//  Copyright © 2016 Freely Creative. All rights reserved.
//

import UIKit
var USER = String()
var USERTYP = String()
class LoginViewController: UIViewController, UITextFieldDelegate {

    @IBOutlet weak var username: UITextField!
    @IBOutlet weak var password: UITextField!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(LoginViewController.dismissKeyboard))
        
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
        
        // Authentication
        
        
        if (username.isEqual(to: "") || password.isEqual(to: "")) {
            
            let alertController = UIAlertController(title: "Oops!", message: "Please be sure to enter login information before attempting to login.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
                print("Alert Dismissed")
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
            
        } else {
            
            // database login here
            let myURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/login.php")
            var request = URLRequest(url:myURL!)
            request.httpMethod = "POST"
            
            let postString = "username=\(username)&password=\(password)"
            
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
                        let getTyp:String = ("\(parseJSON["type"])")
                        
                        
                        
                        if messageToDisplay == "Incorrect password or username combination." {
                            DispatchQueue.main.async {
                                let OKAction = UIAlertAction(title: "OK", style: .default) {
                                    (action:UIAlertAction) in
                                }
                                myAlert.addAction(OKAction)
                                self.present(myAlert, animated: true, completion: nil)
                            }
                        } else if messageToDisplay == "Success, you are being logged in." {
                            DispatchQueue.main.async {
                                USER = username as String
                                let baseType = (getTyp.replacingOccurrences(of: "Optional(", with: ""))
                                USERTYP = (baseType.replacingOccurrences(of: ")", with: ""))
                                self.performSegue(withIdentifier: "loginSuccess", sender: self)
                            }
                            
                        } else { //user is not authorized
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
