davekok\controller
================================================================================

A general purpose controller.

THIS IS A PROOF OF CONCEPT, NOT PRODUCTION READY.

Purpose
--------------------------------------------------------------------------------

The purpose of this controller is to replace the webserver as the central hub in a network architecture. Allowing clients to communicate via the hub with each other. At minimal HTTP will be supported, so http requests can be accepted and routed to a worker. Workers will also be able to communicate with each other.

Architecture style
--------------------------------------------------------------------------------

The architecture style that is followed is the good old unix style. Which perhaps could be named a client oriented architecture as apposed to the service oriented architecture. In SOA all services are also stream servers. Adding the complexity of having to solve handling multiple streams in each service. Often require multi-threaded or asynchrous programming with fibers/co-routines. Non of this is easy even if abstracted by a framework.

Client oriented architectures off loads handling multiple streams to a single stream server (or a cloud of stream servers in high availablity mode). The stream server then takes care of serializing all incoming requests/messages over a single stream. Multiple clients of the same type could be started to handle a high load.

For this to work properly the vocabulary used in the requests/messages/events should be UI focused and not in the vocabulary of the model of clients. The controller should facilitate composition of the view of multiple clients output to the same view.

Examples of this architecture are:
- Bash with it terminal client as the view, bash as the controller and any programs running under bash as the model. Vocabulary is centered around characters.
- X11, with the display server as the view, the desktop manager as the controller and any program as the model. Vocabulary is centered around drawing primitives and pixel buffers.
- Old school PHP, with the webbrowser as the view, Apache as the controller and PHP running in CGI mode as the model. Vocabulary is HTML/CSS.

In this controller vocabulary shall be centered around webcomponents. Webcomponents implement HTML/CSS. HTML/CSS implement drawing primitives.

### Comparison

|                   | COA                    | SOA
|-------------------|------------------------|-----------------------------|
| IO                | Synchronous            | Asynchronous                |
| Streams           | Single                 | Multiple                    |
| Threads/Fibers    | No                     | Yes                         |
| Output vocabulary | UI focused             | Domain focused              |
| Input vocabulary  | Data entry focused     | Invocation/command focused  |
| Standalone        | No requires controller | Yes, controller is embedded |
| Cinefin tag       | Obvious                | Complicated                 | 

In client oriented architecture only the stream server needs to deal with multiple streams. All others simply off load handling multiple streams to the stream server. And read request by request in an infinite loop.
