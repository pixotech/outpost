# Responders

When a Request is received, Outpost builds a queue of available Responders. Each Responder should either return a Response, to be sent to the requesting agent, or throw an `UnrecognizedRequestException`, causing Outpost to move on to the next available Responder.

If no Responder is able to handle the Request, an `UnrecognizedRequestException` is thrown by the Site.

Responders have access to the current Request via the `getRequest()` method. The Responder class also provides access to these Site methods: `getCache()`, `getClient()`, and `getLog()`.