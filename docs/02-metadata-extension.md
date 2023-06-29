Mirror from: https://git.mills.io/yarnsocial/yarn/src/branch/main/docs/_posts/2021-10-09-metadataextension.md
Version: 2021-12-29

---

In the wild several twtxt users came up with metadata comments in their twtxt
files for different purposes. So the **Metadata** extension is actually not
invented at [twtxt.net](https://twtxt.net/). However, this specification tries
to settle on a common standard as extension to the original [Twtxt File Format
Specification](https://twtxt.readthedocs.io/en/latest/user/twtxtfile.html).

## Purpose

Twtxt feed authors might want to provide additional information for their feeds
or about themselves which clients can pick up and display in a suitable way.

## Format

Whenever a physical line in a twtxt file starts with a hash sign (`#`) it is
considered a comment. The comment ends at the end of the line. Comments are
usually ignored by twtxt clients and can appear anywhere in a feed. Comments
must not be preceded with whitespace.

```
# this is a comment
### # this one, too
```

All metadata are part of comments, so they can be easily ignored by clients
which do not support metdata.

Metadata are simple key value pairs, they consist of a field name and a field
value. Field names and values are separated by an equal sign (`=`). Each field
is on its own physical line. Field names are case insensitive and can contain
any number of ASCII letters, digits, minuses and underscores. Whitespace is not
allowed as part of the field name. Field names must consist of at least one
character.

Field values start after the first equal sign (`=`) if that follows a valid
field name. They are case sensitive and can contain anything, there is no
character restriction other than line breaks. Values end at the end of the
line. Multiline values must use the Unicode line separator `U+2028` just like
[multiline twts](multilineextension.html) do.

All whitespace around field names and values must be stripped. Both field names
and values must not be empty. There must be no more than one hash sign (`#`)
preceding fields. If fields cannot be parsed, they must be ignored and treated
as regular comments.

```
# field-name = field value
```

It is legal for the same field name to appear more than once. The format allows
this, but it doesn't make sense for all fields. For example, it is reasonable
to have many `follow` fields (one for each feed that a user is following), but
you probably won't find multiple `description` fields. The order of fields with
the same name must be kept so clients can work with them properly.

Valid meta data examples are:

```
# url = https://example.com/twtxt.txt
#nick=joe
#description =This feed tells about my everyday adventures.
```

## Standardized Fields

This section describes common fields and their purposes.

### `url`

This specifies the URL(s) of the feed. There might be several `url` fields in a
single feed. Feeds may be served over multiple protocols, e.g. HTTPS, HTTP and
Gopher. Dedicated `url` fields advertise the available choices. The first `url`
field value will be used for [twt hashing](twthashextension.html).

#### Security Considerations

Clients must not automatically change the URL to actually fetch the feed based
on this field. However, they might ask the user for confirmation to update the
feed URL if they detect that the fetch URL does not match any of the provided
`url` field values.

Automatically updating the feed URL could result in feed spoofing without the
user noticing.

### `nick`

This is the feed author's nick name. When following feeds clients can suggest
to go with this nick.

For security reasons clients should not automatically update local nicks
without user consent. Otherwise users can be tricked into believing twts are
coming from somebody else they're following. Clients should ask the users when
a nick change is detected.

### `avatar`

This specifies the URL for the author's or feed's avatar, so it can be
displayed along twts, e.g. next to the author. The avatar image is typically in
JPEG, PNG or WebP format. Different clients prefer different ratios, so there
is no strict rule to follow for feed authors. Often avatars are square.

If the `avatar` field is missing, it is up to the client how to visually
represent the author's feed. Some clients such as
[yarnd](https://git.mills.io/yarnsocial/yarn) will automatically generate an
avatar based on available data like the feed's preferred nickname and its URL.

### `description`

The `description` field contains an explanation what the feed is about. Clients
might display this information in a feed details view.

### `follow`

Publicly discloses that the feed author is following another twtxt feed. This
can be helpful to aid feed discoverability. The value contains the nick and the
URL of the feed separated by whitespace:

```
# follow = joe https://example.com/twtxt.txt
```

### `following`

The number of feeds the author is following.

```
# following = 42
```

### `followers`

The number of followers this feed has.

```
# followers = 23
```

### `link`

A link to some other resource which is often connected to the feed or author.
Similar to `follow` fields the syntax for `link` values consists of a link text
followed by whitespace and the actual URL. However, the link text can contain
whitespace.

```
# link = Blog https://example.com/blog/
# link = All my source code https://git.example.com/
```

### `prev`

This field is used by the [Archive Feeds Extension](archivefeedsextension.html).


### `refresh`

This optional field is used by feed authors as a hint to clients to control how often they should fetch or update this feed.

The value of this field is seconds represented by an integer.

**NOTE:** An empty, bad, or unparsable value is ignored.

### `lang`

### `version`

## Changelog

* 2021-10-09: Initial version.
* 2021-10-31: Clarify that metadata field values must not be empty.
* 2021-12-26: Clarify that clients must not automatically change the feed URL to fetch the feed based on `url` metadata field values.
* 2021-12-29: Add new `refresh` metadata field to hint to clients how often a feed can or should be fetched.
* 2023-06-29: Added `lang` metadata, and version