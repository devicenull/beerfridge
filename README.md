PHP app to keep track of inventory in my beer fridge.

"supports" a USB barcode scanner to add/remove inventory.

If untappd ever approves me, I'll probably integrate with that.

Not the greatest example of secure code, but if you're on my network I don't really care...

Definitely don't expose this to the internet!

The /help.php page contains some useful barcodes - printing this is probably the best bet, scanning from screens is kinda.. flakey.

== Barcode Scanner Setup ==
This was tested with a Symbol DS3508 - but anything that supports the relevant configuration options and emulates a keyboard should work.  This configuration lets the barcode scanner submit text as long as the tab/browser is in the foreground, regardless of what the user is doing (although if something is scanned it'll blow away whatever's on the page).

Config:

* Prepend barcodes with a Ctrl + 2 character
* Append barcodes with a ENTER character

For the 3508, scan these in order:

* ![](images/enable_function_keys.png)
* ![](images/prefix_suffix.png)
* ![](images/configure_prefix.png)
* ![](images/1.png)
* ![](images/0.png)
* ![](images/0.png)
* ![](images/0.png)
* ![](images/configure_suffix.png)
* ![](images/1.png)
* ![](images/0.png)
* ![](images/1.png)
* ![](images/3.png)

If this was a new barcode scanner to you, you should factory reset it with the following (before scanning all of the above):

![](images/factory_default.png)



== Untappd API Setup ==

* define UNTAPPD_CLIENT_ID and UNTAPPD_CLIENT_SECRET in config.php
* https://untappd.com/oauth/authenticate/?client_id=CLIENTID&response_type=code&redirect_url=http://path_to_app/auth.php
* define UNTAPPD_USER_CODE from the auth output (untappd says this never expires)
