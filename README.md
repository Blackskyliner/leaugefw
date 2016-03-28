#LeagueFw

This little framework is all about using ThePHPLeague components where possible.
It adds some compatibility or accessibility layers around them as needed.
The whole framework is intended for small to middle sized applications.
Where possible it will using correct type hinting and the framework also tries to be explicit about almost everything.

The Frameworks intend is to only use automatic dependency resolving and not injecting the Container directly into Services.
This is not a hard-limit but bridge components of the framework will be built with this in mind.

There will be an Interface for every compatibility layer of the LeaugeFw. If possible the PSR Interfaces are used.
So it should be easy to make the whole think compatible with almost every Framework out there.

The DI Container can also be reconfigured within the Bootstrap process. In fact the supplied Bootstrap is not really needed.
It will just ease the usage, as it just initializes some core components from ThePHPLeauge.

## Requirements

The only requirement, beside the composer stuff, is PHP7 as I won't look back at old stuff.
It may run on 5.5+, I guess. Maybe I will setup some tests soon and get this checked...

## Structure

There are no by-convention and everything must be configured to be usable.
However there are some recommendations:

- `app/` contains the whole application code. <br/>
  `examples/` shows an possible directory setup.
- `lib/` will be moved into its own Composer package later on.
- `public/` is the facing part of the application and only this one should be accessible from the web.
- `var/` holds all generated cache data and/or dynamic configuration data, like yml or ini configurations.

The benefit of using this structure is that it's almost instantly compatible with some 3rd party tools.

The `app/Resources/` and `public/` structure makes the configuration and usage of `laravel-elixir` a breeze.

Some modern PHP applications also store their configuration and cache within a `var/` folder, so it's easier
to find those data when searching through the application folders.

## Why another framework and not <your-favorite-here>?

It's not about "Whoohoo, I created another, in my opinion super easy, super fast or super duper configurable micro framework!!!!!!11!1 Lets Microbenchmark it!1!11"
Its all about the "No Framework" approach, like described [in this repository here](https://github.com/PatrickLouys/no-framework-tutorial).
Composer makes it somewhat easy and I though why not as many components from one namespace as possible?

So I just wanted to hack something together just to get used to all the processes and maybe to use it in some small private projects.
I also feel that some frameworks just suck for me, because you'll need some 'idehelper.php' to get proper hinting in your favorite IDE.
Some others have the problem that they are too "global" for me, I am not a fan of this facades stuff introduced by laravel.

I just like real proper Container DI, with autowiring, which however must be configured explicitly first.
No magic involved please! Because we all know, magic must be cached and magic sometimes get out of hand.
