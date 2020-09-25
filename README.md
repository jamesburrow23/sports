## Notes
After I completed this last night I realized that there is a potential bug in my logic.

I do some checking to verify that teams are approximately the same ranking when I assemble the roster, however this result could be skewed if there is a large data volume of very experienced players that inadvertently get assigned to the same team.

Were this a real use case I'd rework that assignment to account for this potential influx of talent, by either changing how my players are sorted or by verifying the team rankings afterwards and adjusting as needed (reassigning players, etc)
