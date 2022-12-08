1. 
2. Install [git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
    - Once installed, set it up with your credentials from GitHub.
2. Configure git
    - Run the following command, making sure to use the email address associated with your GitHub account. <br />``git config --global user.email 'your@email.com'``
    - Run the following command, making sure to use the full name associated with your GitHub account. <br/> ``git config --global user.name 'Your Name'``
3. Double check entered information in your [GitHub](https://github.com/settings/profile)
4. SSH keys:
      - SSH keys are a way to identify trusted computers without involving passwords. The steps below will walk you through generating an SSH key and then adding the public key to your GitHub account.
      1. Checking existing keys: 
         - Run the following command (navigate to folder where ssh are stored) <br /> ``cd ~/.ssh`` 
         - Check the directory listing to see if you have files named either **id_rsa.pub** or **id_dsa.pub**. If you don't have either of those files, follow the steps in Configuring a New Key below. Otherwise, skip ahead to Add Your Public Key to GitHub below.<br /> ``ls -al``
      2. Configuring a New Key:
         - To generate a new SSH key, copy and paste the commands below, making sure to substitute in your email. The default settings are preferred, so when you're asked to "enter a file in which to save the key" just press enter to continue. <br /> ``eval 'ssh-agent -s'`` <br /> ``ssh-keygen -t rsa -C "your_email@example.com"``
         - Next, you'll be asked to enter a passphrase. Leave it blank (just hit enter without typing any other characters).
         - Add your new key to the ssh-agent using the following command: <br /> ``ssh-add ~/.ssh/id_rsa``
      3. Add Your Public Key to GitHub
         - From your Terminal, type the following command: <br /> ``cat ~/.ssh/id_rsa.pub``
         - This will give you a big block of characters which you should highlight and copy from your terminal window.
         - Go to your [GitHub settings](https://github.com/settings/keys)
         - Click **New SSH Key**
         - In the **Title** field, add a descriptive label for the new key (your machine name).
         - Paste your key into the "Key" field.
         - Click **Add Key**.
         - Confirm the action by entering your GitHub password.