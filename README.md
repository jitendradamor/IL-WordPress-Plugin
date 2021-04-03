# Imaginelabs
## Points Completed
- A visitor will only able to view the weather forecast for the upcoming 3 days in the city of Beirut, Lebanon.
- Admin setting page for adding API key from the admin Panel

## Admin Login Details  
http://mydemoserver.site/imaginelabs/wp-admin
Username : admin@login  
Password : admin@login

Accu Weather Admin Option page to save the API key : http://mydemoserver.site/imaginelabs/wp-admin/options-general.php?page=accuweather


## Steps to Transfer the website from one domain to another domain
1. Copy the whole directory to the destination location
2. Export the database from the current Hosting and import the database into the new hosting
3. Install the Search and Replace plugin into the root directoty of the new hosting ( Don't put the plugin in the "plugins" directory  )
4. The plugin will provide 2 input fields, Old URL and New URL. We need to update both URL and then need to run DRY run to test the effect on the database. Once the DRY run done successfully we can press the LIVE run. It will update the site URL in the site


## IF the Weather response is not visible then we need add new API key into the site as FREE API key is only valid for 50 API request