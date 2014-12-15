
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
INSTRUCTIONS:

1: /htdocs/CASSA/* 
1) contains all relevant files associated with this project 'MegaLan'. 
   Please copy/paste this folder into your local machine.
   `xampp/htdocs/`

2: /mysql/cassa_lan.sql
2) contains the creation script for MegaLan's database.
   This script contains POPULATED DATA for viewing purposes. 

2.1: /mysql/EMPTY_cassa_lan.sql
2.1) contains the creation script for MegaLan's database.
     This script contains POPULATED DATA FOR MANDATORY TABLES ONLY [faq, contact, news, client(admin)]. 
     There is only 1 client that exists in the system by default which is ADMIN (super user). 

     +++++++++++++++++++++++
     |                     |
     | ADMIN LOGIN DETAILS |
     | ~~~~~~~~~~~~~~~~~~~ |
     | u: admin@domain.com |
     | p: admin		   |
     |                     |
     -----------------------

3: /mysql/data/cassa_lan/*
3) contains all relevant FILES associated with this projects database (POPULATED DATA).
   These files should be used for last resort, incase the above scripts do not execute.
   
3.1: /mysql/data/EMPTY_cassa_lan/*
3.1) contains all relevant FILES associated with this projects database (EMPTY DATABASE).
   These files should be used for last resort, incase the above scripts do not execute.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
