# Welcome to PWPOP

PWPOP is a kick-off of an e-commerce following requirements at a La Salle Campus Barcelona's subject "Web Development 2" teached by [Jaume Capdevila](https://github.com/jaumecapdevila) on 2019.

This project is based on a Full Stack development through PHP's microframework Slim for the route management and Twig as a library for the client side. To execute the project, it is recommended to setup a Laravel Homestead Vagrant machine. Help can be provided by [Pablo Gómez](mailto:pgomezponce@gmail.com).

The following content is a documentation based on the task submission requirements, explaining Time assignment, Organization and a few comments.

# PWPOP Group 06

This document contains all information related to the solution deployed by group 06 formed by:

- Laura Gendrau <laura.gendrau@students.salle.url.edu>
- Pablo Gómez <pablo.gp@students.salle.url.edu>

There's a list with everything that will be **commented** here.

- [PWPOP Group 06](#PWPOP-Group-06)
  - [Time inverted](#Time-inverted)
    - [Planification](#Planification)
    - [Coding](#Coding)
    - [Revision](#Revision)
    - [Time Summary](#Time-Summary)
  - [Organization](#Organization)
  - [Other comments](#Other-comments)

## Time inverted

In order to develop this project, our group has organized in 3 phases:

1. Planification
2. Coding
3. Revision

### Planification

This phase had as goal to define project structure and it's style guide.
During the first week, each member contributed in a Figma project to get a reference mock up.

This path was taken in order to align each member towards the same goal and develop a clear image for every page/component needed.

This action has taken about 4 hours.

### Coding

This was the longest part of our project as it was the more complex one.

This phase could be divided in each module this project has:

- View (5 hours)
- Model (6 hours)
- Controller (14 hours)
- Settings/Configuration (5 hours)

Each part had some inconvenients at it's development, as there were some issues like the following ones:

- **View**
  - We had some issues at including assets from our 'public/assets' directory. Solved using `{{base_url()}}` on each asset needed
  - We started using lots of atributes instead of a single atribute `{'product':['id':403,'name':'Product1']}` with multiple propierties `{'idProducte':403, 'nomProducte':'Product 1',...}` and our logics were crashing when we had multiple elements with same propierties.
- **Model**
  - We started using a single SQL interface in our solution, but we finally divided the connection if was oriented to User information or Product information.
- **Controller**
  - We had lots of problems using flash when some message needed to be shown.
  - We had problems returning response codes with errors, but was finally solved.
- **Settings/Configuration**
  - Developing our SQL model took us some time as there were some changes related to a `isActive` flag.

### Revision

Check if every part made in [Coding](###Coding) was correct or not. It took about 5 hours.

### Time Summary

The total ammount of hours was of **39 hours**

## Organization

All code is stored at a private GitHub repository hosted by Pablo Gómez <pablo.gp@students.salle.url.edu>, and all the other members were added as collaborators at said repository. 

All members had an active role in this project. As it was a complex work, each member had some areas of expertise, where said member would be the leading voice about those concepts related to said area.

Despite all this division, all members have gone through almost all code as it was needed to check (at least) to prevent code replication and merge conflicts at our git repository.

## Other comments

We used MD5 encryption to store passwords as it's a "safe" method, which is oriented to get a irreversible answer.
