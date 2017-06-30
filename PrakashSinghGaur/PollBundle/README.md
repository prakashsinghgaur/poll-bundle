Introduction

This is an opinion poll bundle using which you can create simgle question polls (aka monkey surveys) in which authenticated users can submit their votes in form of Yes, No and Not Sure.


Following points for consideration:-
1. You are using Twitter Bootstrap and Font Awesome for your UI scheme. If not then you have to manage twig templates on your own. Otherwise, these will be looking ugly. Templates are inside /Resources/views/  folder
2. You have a user entity class called 'User'. Work is in progress to make this user entity configurable. But for the time being the default user entity class is User for referencing topics and comments to different users in the application.
3. The priviledge for creating new Forums may be given to a specific role e.g. Admin by making necessary access control entries in security.yml file and securing /pollmanage path.
4. Authenticated users can submit their votes and once they do they will be presented with a Bar Chart showing final results count of the poll
5. Editing or deleting of answers are not allowed
6. Admins can create Poll questions, edit, disable and delete them



Installation

Download and install using composer require prakashsinghgaur/poll-bundle:dev-master

Register the bundle in AppKernel in registerBundles():
new PrakashSinghGaur\PollBundle\PrakashSinghGaurPollBundle(),

Import routing in routing.yml
prakash_singh_gaur_poll:
    resource: "@PrakashSinghGaurPollBundle/Controller/"
    type:     annotation
    prefix:   /

To create relevant  tables in the database run this console command from the root of your project
php app/console doctrine:schema::update --force


Usage

After installation, admins can start by visiting /pollmanage url and users can visit /polls url to see various active polls and submit their votes. You can use these URLs in your navigation.