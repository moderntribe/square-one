#  === Service Providers ===

Service providers are central to the container environment used in this framework.  You'll notice in the core/src/Core.php file it is strictly used for registering service providers to the container.

The service providers handle registering components and any listeners (actions/filters) and other needed bindings.

Every custom post type, meta group, taxonomy and other components created within the core plugin will be registered inside a service provider class.

### Organization

Beyond the bootstrapping functionality of Core and the Service Providers, they don't do much but proper organization is vital to the namespace and container setup.

There are only two directories within the core/src/Service_Providers directory:

* Post_Types
* Taxonomies
 
All other service providers have their file within the Service_Providers directory.

Each post type and taxonomy have an individual service provider file whereas post meta has a single Post_Meta_Service_Provider file for handling all post meta classes.

Post types and taxonomies have service provider classes that are children of Post_Type_Service_Provider and Taxonomy_Service_Provider.

### Container

When registering a service provider stick with class names for example:

```$container['post_type.sample_post_type']```

Or if their is a utility class alongside your custom post type and in the same name space you would use

```$contianer['post_type.sample_post_type.sample_component']```

It's important to keep proper namespacing.

### Notes

The **Global_Service_Provider** class handles the classes that work with the **Posts 2 Posts** plugin. 

Learn more about Post 2 Post here and usage in this project here: [Posts 2 Posts](p2p.md)