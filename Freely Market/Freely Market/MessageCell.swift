//
//  MessageCell.swift
//  Freely Market
//
//  Created by Austin Mitts on 4/3/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import Foundation
import UIKit

class MessageCell: UITableViewCell {
    
    
    @IBOutlet var lblName: UILabel!
    @IBOutlet var lblMessage: UILabel!
    
    
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
        
    }
    
    
    
    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)
        // Configure the view for the selected state
        
        
    }
    
}
