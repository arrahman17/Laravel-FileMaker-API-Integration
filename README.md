# Laravel Package for FileMaker Integration
# Package info

This package is responsible for fetching/adding/deleting data from FileMaker using the FileMaker Official API


# There are some steps to follow:

 **Update the Database Configuration php file** 

 - add the FileMaker API credentials:

**add this to composer** 
 
 - in require =>  "Netmarket/FileMaker": "^1.0.0" 
 - then add this below require
 - "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/arrahman17/Laravel-FileMaker-API-Integration"
        }
    ],

**In Terminal run just** 

- "composer update"

**USE**
- Use the Database.php via Dependeny injection in controller or other class in the constructor for example,
-  public function __construct(Database $DB)
      {
          $this->Database = $DB;
      }
