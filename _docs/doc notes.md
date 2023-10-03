# Documentation

# Modify your Project model

```
use Yormy\ProjectMembersLaravel\Traits\HasMembersTrait

class Project extends Model
    ...
    use HasMembersTrait;
    ...
```

Now several functions become available on your projects model

### $project->members
all the members of your project

### $project->isOwner($member)
returns true if the passed in member is a owner of the project

### $project->owners
returns all the owners

### $project->invites
returns the current pending invites for this project

### $project->hasMember($member)
returns bool if the passed in member is a member of your project

## Events
MemberInvitedEvent
MemberAcceptedInviteEvent
MemberDeniedInviteEvent
MemberLeftProjectEvent
