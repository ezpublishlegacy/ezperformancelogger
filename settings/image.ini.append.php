<?php /*

# We can we use a set of "tracing" image handlers to get KPIs related to
# image conversion activity, even when DebugOutput is disabled.
# Uncomment one of the following lines for that, according to your version of
# eZ Publish.
# If you have other eZP versions than 4.5 - 5.4, take example from the
# code given in classes/tracers/4.x, and create your own

[ImageMagick]
# ONLY UNCOMMENT ONE LINE
#Handler=eZImageTracing45ShellFactory
#Handler=eZImageTracing46ShellFactory
#Handler=eZImageTracing47ShellFactory
#Handler=eZImageTracing50ShellFactory
#Handler=eZImageTracing51ShellFactory
#Handler=eZImageTracing52ShellFactory
#Handler=eZImageTracing53ShellFactory
#Handler=eZImageTracing54ShellFactory
