# Stage Anonymizer

Anonymize user data in your stage environment.

## Table Of Contents

* [Installation](#installation)
* [Usage](#usage)
* [License](#license)
* [Contributing](#contributing)

## Installation

The best way to use this package is through Composer:

```BASH
$ composer require websupporter/stage-anonymizer
```

But you can also download the zip and install it in the regular way.

## Usage

After you have coppied your production database into your stage environment, you can navigate to Tools > Erase Personal Data. You will find a button "Stage Anonymizer". Once you hit the button, it will run the registered Erasers. In WordPress 4.9.6 this means the comments will be anonymized. For the anonymization of the stage database, there is an extra eraser registered, which will anonymize the default data of a user, like his first name, last name, email address etc. The current user as well as administrators are currently not anonymized.

Remember: The current state of this project is a "prove of concept". You are very welcome to test it and if you think its useful, also to use it, but do not be suprised, if you experience any bugs and similar stuff. So, use at your own risk :)

## License

Copyright (c) 2018 David Remer, Websupporter

Good news, this plugin is free for everyone! Since it's released under the [GPL-2.0 License](LICENSE) you can use it free of charge on your personal or commercial website.

## Contributing

All feedback / bug reports / pull requests are welcome.
