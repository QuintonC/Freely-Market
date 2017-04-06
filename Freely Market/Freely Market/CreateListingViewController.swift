//
//  CreateListingViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 4/6/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import UIKit

class CreateListingViewController: UIViewController, UITextFieldDelegate, UITextViewDelegate, UIPickerViewDelegate, UIPickerViewDataSource, UIGestureRecognizerDelegate {

    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var itemTitle: UITextField!
    @IBOutlet weak var itemPrice: UITextField!
    @IBOutlet weak var descBody: UITextView!
    @IBOutlet weak var selectButton: UIButton!
    let pickerOptions = ["Rental Listing", "Sale Listing", "Equipment Listing"]
    @IBOutlet weak var listingTypeButton: UIButton!
    @IBOutlet weak var listingOptions: UIPickerView!
    @IBOutlet weak var doneButton: UIButton!
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // Do any additional setup after loading the view.
        
        // Gesture recognizers for handling taps outside of keyboard or uipicker
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(CreateListingViewController.dismissKeyboard))
        let done: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(CreateListingViewController.doneTapped))
        tap.delegate = self
        done.delegate = self
        view.addGestureRecognizer(tap)
        view.addGestureRecognizer(done)
        
        // Handlers for menu button
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        // Setup for the text area for description
        self.descBody.layer.cornerRadius = 5.0
        descBody.text = "Enter your item description here."
        descBody.textColor = UIColor.lightGray
        
        // Set delegates, etc for uipicker and hide some stuff
        listingOptions.delegate = self
        listingOptions.dataSource = self
        listingOptions.showsSelectionIndicator = true
        listingOptions.isHidden = true
        doneButton.isHidden = true
        
        // set targets for the buttons
        listingTypeButton.addTarget(self, action: #selector(CreateListingViewController.typeTapped), for: .touchUpInside)
        doneButton.addTarget(self, action: #selector(CreateListingViewController.doneTapped), for: .touchUpInside)
        
        // set target for the price field
        itemPrice.addTarget(self, action: #selector(priceFieldChanged), for: .editingChanged)
    }

    func textViewDidBeginEditing(_ textView: UITextView) {
        if descBody.textColor == UIColor.lightGray {
            descBody.text = nil
            descBody.textColor = UIColor.black
        }
    }

    func textViewDidEndEditing(_ textView: UITextView) {
        if descBody.text.isEmpty {
            descBody.text = "Enter your item description here."
            descBody.textColor = UIColor.lightGray
        }
    }
    
    func priceFieldChanged(_ textField: UITextField) {
        if let amountString = textField.text?.currencyInputFormatting() {
            textField.text = amountString
        }
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    func numberOfComponents(in pickerView: UIPickerView) -> Int {
        return 1
    }
    
    func pickerView(_ pickerView: UIPickerView, numberOfRowsInComponent component: Int) -> Int {
        return pickerOptions.count
    }
    
    func pickerView(_ pickerView: UIPickerView, titleForRow row: Int, forComponent component: Int) -> String? {
        return pickerOptions[row]
    }
    
    func pickerView(_ pickerView: UIPickerView, didSelectRow row: Int, inComponent component: Int) {
        listingTypeButton.setTitle(pickerOptions[row], for: .normal)
    }
    
    func typeTapped() {
        listingOptions.isHidden = false
        doneButton.isHidden = false
    }
    
    func doneTapped() {
        listingOptions.isHidden = true
        doneButton.isHidden = true
    }
    
    // allow multiple gesture recognizers
    func gestureRecognizer(_ gestureRecognizer: UIGestureRecognizer, shouldRecognizeSimultaneouslyWith otherGestureRecognizer: UIGestureRecognizer) -> Bool {
        return true
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

extension String {
    
    func currencyInputFormatting() -> String {
        var number: NSNumber!
        let formatter = NumberFormatter()
        formatter.numberStyle = .currencyAccounting
        formatter.currencySymbol = "$"
        formatter.maximumFractionDigits = 2
        formatter.minimumFractionDigits = 2
        
        var amountWithPrefix = self
        
        let regex = try! NSRegularExpression(pattern: "[^0-9]", options: .caseInsensitive)
        amountWithPrefix = regex.stringByReplacingMatches(in: amountWithPrefix, options: NSRegularExpression.MatchingOptions(rawValue: 0), range: NSMakeRange(0, self.characters.count), withTemplate: "")
        
        let double = (amountWithPrefix as NSString).doubleValue
        number = NSNumber(value: (double / 100))
        
        guard number != 0 as NSNumber else {
            return ""
        }
        
        return formatter.string(from: number)!
    }
    
}
