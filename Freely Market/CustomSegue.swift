//
//  CustomSegue.swift
//  Freely Market
//
//  Created by Austin Mitts on 4/4/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import UIKit


class CustomSegue: UIStoryboardSegue {
    
    
    override func perform() {
        let sourceViewController = self.source
        let destinationController = self.destination
        let navigationController = sourceViewController.navigationController
        // Pop to root view controller (not animated) before pushing
        if self.identifier == "newMessage"{
            navigationController?.popViewController(animated: false)
            navigationController?.pushViewController(destinationController, animated: true)
        }
    }
}
