# monero-show

A website for offering view wallet scanning. Run completely by Docker-Compose, and using the monerophp library from the Monero Integrations team.

DO NOT USE the code as it exists today. This is (barely) functioning concept code in the current state.


This project is currently abandoned. I no longer see the point in it. Here are some unorganized notes about what I was thinking: 



The only thing I have seen anyone take issue with on Seraphis is the view wallet capability.
Start AND finish by emphasizing this is not an actual problem.
desire to have a solution ready on day 1 of the Seraphis fork.

Seraphis View Potential Issues:
1. On-chain privacy impact
2. Compliance Requirements

1. In the wildest theory you can imagine this is possible; however, to eliminate 64->10 decoys to get back to where we are today would require ~78% of view keys being aggregated by a centralized entity. It is practically unthinkable.
2. We alraedy have view capability, and the entire view capability that would be useful to exchanges monitoring deposits. This does not change the playing field.

Lets image that on day one of the Seraphis hardfork, the malicious actor whatever.com accounces that it is offering near instant wallet synchronization. It collects all view keys and sells the data to blockchain analytics firms. This seems like it should be avoided, and the easy answer is to offer a decentalized scanning service for people to use instead. This is not a perfect fix, but we'll get to that later.

My project was to make it as easy as possible for anyone to offer wallet scanning services, and to setup the framework to do this. I envisioned scanning servers, and frontend servers. The fully decentralized version of this system is that anyone can start running a scanning server, establish a connection with any frontend servers, and begin handling a proportionate number of scan requests.

This is a deeply flawed idea, in that an attacker can simply create a number of scanning servers that dwarfs the actual decentralized network and they would be handed the majority of view data being processed. I think this is possibly better than the centralized service. If an attacker on the decentralized system is running 80% of all scanning nodes they will 'only' gather 80% of the data. The centralized system attacker gathers 100%. So where does that leave this system?

There is a clear downside to both a fully centralized and a fully decentralized service. Looking at the middle ground between these, I can imagine an approach where a given frontend selects which scanning nodes it uses. A frontend could curate the scanners it uses (or not). You are left with individual services that can operate as a fully decentralized service where a frontend uses all available scanners, or as a more decentralized service where a frontend uses whatever set of scanners it finds to be suitable.

Having frontends select the scanners it uses is undesirable, in that it creates the trusted setup that Monero (and I) am allergic to. Perhaps it isn't so terrible in this case, as a user can pick from (or create) any setup that they actually trust. There is no insistance on actually trusting anyone. Instead a user is given a selection of people to trust if they want, and a fully decentralized service to use if they want (a bad idea, but sure).

So now the list of scanning choices has 3 parts:

1. fully centralized
2. fully decentralized
3. A collection of services with varying levels of centralization

This becomes an opt-in trust solution for handling an opt-out privacy feature. I have not been able to come up with anything better.

Want to get to a place where a majority of view keys given over for scanning are not collected. 

Only scanning servers see view keys.
Threat that a frontend is running scanning servers.

frontends can be monitored by the scanners it connects to. A frontend could publish the last time it sent a request to each scanner. Someone running a scanner it uses could then submit a viewkey as a test to confirm that the frontend registers the request, and is proportioning requests properly.

Neat situations around view keys before-after hardfork. There isn't anything I could do to affect compliance action regardless, so I wanted to look at the risk of aggregating view keys to on-chain privacy. The easiest thought is to decentralize scanning.

Cool thing where you can check a cold wallet with Seraphis view key if your wallet is in the 'old' chain. If it sees nothing, you are good and nothing is revealed about the chain today!

if we are confident that most keys are not collected most of the time, that's mission accomplished.

I'm left feeling more optimistic about Seraphis than ever.

I am making something happen that I shouldn't. The response is ready if a centralized scanning service becomes highly prominent. Until then, do nothing.
