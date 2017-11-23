# Square One Permissions Framework

## Sections

The Section taxonomy is used as the primary organizational mechanism for the site.
Every piece of content on a site is assigned to a term in the Section taxonomy, and
the admin UI is always filtered to show only a single Section.

Users can be assigned to one or more Sections. To switch which Section they are working
in, there is a UI that sits at the top of the admin. It lists all Sections the current
User has access to, and allows for quickly switching between Sections. Administrators have
access to all Sections. The code powering the Section switcher can be found in the
`Section_Switcher` class.

Whenever content is created, it is automatically assigned to a Section by the
`Section_Assigner` class. It uses whichever Section is currently selected by
the `Section_Switcher`, and in the case when content is created by a user that
does not have a Section (e.g., an automated background process), it falls back
to the default Section that can be set on the "Writing" settings page.

## Roles

Users can be assigned to one or more Sections. A user should have the WordPress role of
"Subscriber" (i.e., they should have no capabilities). Each Section assignment (handled
via `Object_Meta\User_Sections` or `Object_Meta\Section_Users`, depending on the screen, but
ultimately delegating to `Section::set_role()`) connects the user to a Section term with
a Role. The Role determines what capabilities the user has within that Section.

Each Role should have a class that implements the `Section_Role_Interface` (and will
in all likelihood extend the `Section_Role` abstract class). This class is responsible
for returning a list of capabilities that a user should have when `Cap_Filter` calls it
during a `user_has_cap` filter.

A `Role_Collection` containing the Roles available on the site should be passed to
the constructor of the `Cap_Filter` instance in the `Permissions_Provider` service provider.

A special Role, the `Null_Role` is returned in certain cases where a user does not have
a Role for a given Section, and grants the user no capabilities for that Section.

## Usage on new projects

Copy the entire `Permissions` directory into the core plugin of your project, and
copy the entire `Service_Providers/Permissions` directory into the `Service_Providers`
directory of the core plugin of your project.

Register the main service provider from your core plugin.

```
$this->container->register( new Permissions_Provider() );
```

From there, you can add/remove/edit roles in the `Roles` directory as appropriate
for your project.