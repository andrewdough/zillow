# Zillow Test
The PHP has some room for improvements as I spent most of the time getting familiar with XML and XSL.

### Front End
Uses jQuery and bootstrap css. Form submission triggers ajax request to search.php, which returns the result HTML.

### Back End
Uses PHP, cURL, and the XSL module. Zillow APIs are triggered with a cURL request and the response XML is styled with searchresults.xsl for result HTML.

