<p align="center">
  <strong>A developer friendly plugin for outputting visitor messages to REST API</strong>
</p>

## Overview

This plugin creates a database table in your wordpress database named _PREFIX_bf_messages.
It then exposes a minimal unsecured API to do the following: 
- create a message
- list an index of all messages
- list a single message

## Structure

- index.html contains the mark-up to be used in your template (style based on need)
- input.js contains the interaction basic js to get your form data
- index.php is the plugin file

## Endpoints

YOUR_WEBSITE/wp-json/messages/v1/all -> GET request , returns all messages from table as JSON

YOUR_WEBSITE/wp-json/messages/v1/create -> POST request (implemented in input.js), creates a new message

YOUR_WEBSITE/wp-json/messages/v1/{id} -> GET where {id} is an integer parameter for your specific message id (e.g. 15)

YOUR_WEBSITE/wp-json/messages/v1/delete -> PUT request for deleting a specific message (no input js, use Postman to clean)

## Requirements

- PHP >= 7.4

## Installation

1. Copy repository folder to wp-content/plugins

   ```
2. Activate plugin (it will issue a warning of unexpected characters when creating the table)


3. Add mark-up from index.html to your page (style as needed, change classes as needed)
4. Add input.js to your page script (change classes and identifiers as needed)


