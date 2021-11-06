davekok\controller
================================================================================

A general purpose network controller for workers.

THIS IS A PROOF OF CONCEPT, NOT PRODUCTION READY.

Purpose
--------------------------------------------------------------------------------

The purpose of this controller is to replace the webserver as the central hub in a network architecture. Allowing workers to communicate via the hub with each other. At minimal HTTP will be supported, so http requests can be accepted and routed to a worker. Workers will also be able to communicate with each other.

Architecture style
--------------------------------------------------------------------------------

The architecture style that is followed is the good old unix style. Which perhaps could be named a worker oriented architecture as apposed to the service oriented architecture. In SOA all services are also stream servers. Adding the complexity of having to solve handling multiple streams in each service. Often require multi-threaded or asynchrous programming with fibers/co-routines. Non of this is easy even if abstracted by a framework.

Worker oriented architectures off loads handling multiple streams to a central stream server (or a cloud of stream servers in high availablity mode). The stream server then takes care of serializing all incoming requests/messages over a single stream. Multiple workers of the same type could be started to handle a high load.

For this to work properly the vocabulary used in the requests/messages/events should be UI focused on output and data entry focused on input and not in the vocabulary of the domain of the workers. The controller should facilitate composition of the view if multiple workers output to the same view. Links can be used by workers to reference each other.

Examples of this architecture are:
- Bash with it terminal client as the view, bash as the controller and any programs running under bash as workers. Vocabulary is centered around characters.
- X11, with the display server as the view, the desktop manager as the controller and any program as a worker. Vocabulary is centered around drawing primitives and pixel buffers.
- Old school PHP, with the webbrowser as the view, Apache as the controller and PHP running in CGI mode as a worker. Vocabulary is HTML/CSS.

In this controller vocabulary shall be centered around webcomponents. Webcomponents implement HTML/CSS. HTML/CSS implement drawing primitives.

### Advantages

- Workers connect as stream or TCP client to a server. Thus then can run anywhere. In the cloud, on promise or mobile (IoT devices). No need to open up firewalls either. The server does not need to know where they are.
- They are easy to write. They just require an infinite loop reading from the main stream. Doing a read will block the stream until a new message/request/event arrives.
- Using the replication feature of Kubernutes for instance, will get you multiple workers. No need to write watch dogs or manager programs. The controller can easily detect a worker offline if a stream is disconnected.
- They can easily be deployed in a container but also without one.

### Comparison

|                   | WOA                    | SOA
|-------------------|------------------------|-----------------------------|
| IO                | Synchronous            | Asynchronous                |
| Streams           | Single                 | Multiple                    |
| Threads/Fibers    | No                     | Yes                         |
| Output vocabulary | UI focused             | Domain focused              |
| Input vocabulary  | Data entry focused     | Invocation/command focused  |
| Standalone        | No requires controller | Yes, controller is embedded |
| Cinefin tag       | Obvious                | Complicated                 | 

Operations
--------------------------------------------------------------------------------

| Command         | Description                                        |
|-----------------|----------------------------------------------------|
| ./ops build dev | to build a dev container                           |
| ./ops build TAG | to build a release container with TAG              | 
| ./ops update    | to update package dependencies (through composer)  |
| ./ops require   | to require a package (through composer)            |
| ./ops push TAG  | to push container to container registry            |
| ./ops check     | to do a syntax check                               |
| ./ops test      | to run the tests                                   |
| ./ops run dev   | to run dev container                               |
| ./ops run TAG   | to run release container with TAG                  |
| ./ops run       | to run latest release container                    |

### Directory structure

The expected directory structure:

- __$SRCDIR__ your source dir
- __$SRCDIR/$GITACCOUNT__ the account name of the fork (davekok if unforked)
- __$SRCDIR/$GITACCOUNT/controller__ checkout of this repo
- __$SRCDIR/$GITACCOUNT/http__ checkout of http repo
- __$SRCDIR/$GITACCOUNT/lalr1__ checkout of lalr1 repo
- __$SRCDIR/$GITACCOUNT/stream__ checkout of stream repo
- __$SRCDIR/$GITACCOUNT/log__ checkout of log repo
