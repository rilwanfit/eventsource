## Event Sourcing library for PHP applications

## What is event sourcing
Event sourcing is a specific procedure for storing data. event sourcing does not persist the current state of a record, but instead stores the individual changes as a series of deltas that led to the current state over time.

### How it is working
It exactly how bank manages an account. The bank does not save the current balance. Instead, it records the deposits and withdrawals that occur over time. The current balance can then be calculated from this data:

- if the account was first opened with a deposit of 500 EUR,
- then another 200 EUR were added,
- and then 300 EUR were debited,

the following calculation takes place:

```
500 (deposit) AccountWasCreated
+ 200 (deposit) AmountWasDeposited
- 300 (payment) AmountWasWithdraw
= 400 (balance)
```
How we can get the balance? it could be done by replaying the events.

### Implementation
1. Lets choose Doctrine to handle database layer


