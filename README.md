# Puzzle-With-FileUploads
## Assignment details
__Due date: 11/14/22__

* ✅ When the page is loaded initially, the default image should be used for the puzzle
  * ✅ This image and all its tiles are stored in the "images" directory
* ✅ Three new buttons are now available in the "control panel" area (provided)
  * ✅"Choose File" which allows you to choose an image for uploading
  * ✅"Upload" which submits the uploaded file
      * When this button is pressed your game should behave in the following way
        * Jarib Pott - If any errors occur, you should display a JavaScript alert and reset the game to the default image
        * Possible errors are:
          * File is not an image
            * allow jpeg, png, and gif images
          * File is not at least 612 pixels on one of the sides
          * File is too large (max size should be 1.5MB)
          * No file was uploaded
        * Leo - All files in the "uploads" directory except those starting with a dot should be deleted
          * use scanDir() and unlink() for this
        * Jon - If all is good with the upload, it should be scaled down to be 612 pixels on the longest side
          * The final scaled image should be modified slightly so that both the height and width are multiples of 3
            * 612 is already a multiple of three
            * The other dimension will need to be reduced by at most two pixels
          * This scaled image should be saved as "guideImage.jpg" in the "uploads" folder
        * After scaling, you should slice the guide image into nine equal-sized tiles and saved as "tile0.jpg" - "tile 8.jpg"
          * For simplicity, save all slices as jpegs
        * Eli - You will create a blank image by doing the following:
          * create a new image resource in php of the proper size
          * Sample the center pixel in the guide image
            * use imagecolorat() for this
          * extract the r, g, b values for that color
            * read the php manual to find out how to extract the rgb values
          * Invert the color extracted and use the inverted color for the blank
          * fill the new image resource with the new color
            * use imagefill() for this
          * Save the image as "blank.jpg" in the "uploads" folder
* ✅"Reset to default puzzle image"
  * When this button is pressed, the page is reloaded using the default image for the puzzle

<br>

# Working with GitHub
### How we should work using GitHub:
I believe using GitHub will help and allow us to use it as a tool now and in the future. <br>
Therefore, I think we should learn how to use branches.
First: I would learn about github branches using this [video](https://www.youtube.com/clip/Ugkxxgn68sIypKs7OcqaAXbsZbi_JItcGrhf) or [this one](https://youtu.be/JTE2Fn_sCZs?t=72), they both help explain branches and how to use them
<br>
Then,
   * 1.) Dowload this project **locally** on your computer through your local terminal or [GitHub Desktop software](https://desktop.github.com/)
   * 2.) Go to the directory, via terminal or GitHub Desktop, where your downloaded project is found and create a new branch to work in:
      * git branch nameOfBranch
      * (replace nameOfBranch with the name of the feature you plan on adding)
   * 3.) Switch to that working branch
      * git checkout nameOfBranch
   * 4.) Open your text editor or terminal to work on your feature and finish it
   * 5.) When you are finished with a feature:
      * git add -A
      * git commit -m "type short message regarding the feature you are implementing"
      * git push
   * 6.) Wait for a group member to review the code and officially implement your code via GitHub
   * 7.) Once your code is implemented, you should recieve a notification and then:
      * delete the branch on your local computer with: git branch -d nameOfBranch
      * then update your main branch with: 
         * git checkout main  
         * git pull
      * repeat steps 2-7
<br> 
I also recommend running 'git pull' each time you get on your terminal to work to ensure you have the latest updates on the files.
<br>

If this method of working doesn't work or seems too much. Let me know and we'll find alternatives. -Jon
