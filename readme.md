# PyroSnippets 2.4

PyroSnippets allows users to create and manage small snippets of reusable content for their site. Useful for giving clients access to small chunks of content that are not page content and can be edited like text, images, WYSIWYG, and HTML code.

## Installation

If downloading from GitHub, rename the downloaded folder to <var>snippets</var>. Upload the <var>snippets</var> folder to your <var>addons/default/module</var> folder or your <var>addons/shared_addons/modules</var> folder. Install via the Modules section in the admin. 

## Using Snippets

After you install Snippets, it'll show up in the **Content** menu of PyroCMS. There are two areas, "Content" and "Setup". Setup is where you create the snippets and define any parameters. Content is where the snippet content is actually edited.

Once you set a snippet, you can see the syntax to display it in your layouts and themes. It always follows this syntax:

	{{ snippet:your_snippet_slug }}

## Snippet Types

### Text, HTML, and WYSIWYG

Simple text-based snippets that allow you to save text content. In the WYSIWYG snippet, you can choose to use the advanced or simple editor.

### Image

Allows you to upload an image. The tag for the image snippet returns the ID of the file, so you can use it like this:

	{{ files:image id=snippet:your_image_slug width="100" height="100" mode="fill" }}

You can find more info on displaying images in PyroCMS [here](http://docs.pyrocms.com/2.1/manual/plugins/files).

## Changelog

### 2.4 - Jun 6, 2013

_Use with: PyroCMS 2.2.x_

* Updates and fixes for PyroCMS 2.2.x

### 2.3 - February 8, 2013

_Use with: PyroCMS 2.1.x_

* Added support for three status levels for snippets: public, logged in users only, and hidden
* Added support for cloud files in the image snippet
* Changed output of image snippet to the file ID. Please see image snippet section above for more details
* Added option to choose Simple or Advanced WYSIWYG editor
* Updates for compatability with PyroCMS 2.1.5

### 2.2.2 – February 8, 2012

* Updated to PyroCMS slug generator (Thanks @Bojch)!
* Refined UX for snippet setup (Thanks @Bojch)!
* Updated the folder structure for easier uploading via control panel
* Updated Slovenian language (Thanks @kubis)!

### 2.2.1 – December 18, 2011

* Updating forms and other elements to PyroCMS 2.0 standards

### 2.2 – November 4, 2011

* Changed to snippets as discrete files system with parameter options
* Separated Snippets into setup and content modes
* Updated syntax example for Lex Parser
* Removed support for chunks syntax

### 2.1 – October 19, 2011

* Upgrading to PyroCMS 2.0 interface styles
* New image snippet type
* Save & Close option for editing snippets
* Slovenian Translation

### 2.0 – September 16, 2011

* Change to PyroSnippets name
* New permissions addition allows you to restrict member groups to just editing snippet content
* Updated interface styles for PyroCMS 1.3

### 1.1 – July 26, 2011

* Updates to work with PyroCMS 1.3
* Autocomplete for chunk slugs
* Shows Chunk type in list

## Authors

* [Adam Fairholm](http://twitter.com/adamfairholm)
* [Stephen Cozart](http://twitter.com/stephencozart)
