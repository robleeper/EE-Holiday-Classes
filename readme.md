# DEPRECATED
This addon is not being maintained.

## Holiday Class

Holiday Class is a plugin for ExpressionEngine 2 that helps you generate CSS classes to make site elements responsive to holidays. On an average day, the plugin will output “standard”. If the plugin detects a holiday, a CSS short name for the holiday will be generated.

## Installation

1. Download Holiday Class
2. Extract the .zip file
3. Copy the holiday_class directory to the /system/expressionengine/third_party/ directory.
4. Done!

## Usage

Place the Holiday Class tag in the appropriate class/id fields of your templates.

`{exp:holiday_class}`

## Settings

The plugin has a single setting you can add: country. Usage is simple enough. If left empty or omitted, the plugin defaults to USA.

`{exp:holiday_class country=“Canada”}`

### Supported country settings:
- USA [default]
- Canada

## Short name cheat sheet

The tags generated are as follows:

**USA [DEFAULT] HOLIDAYS**
- New Years Day							(newyear)
- Martin Luther King Jr. Day			(mlk)
- Valentines Day							(valentines)
- President's Day							(presidents)
- George Washington's Birthday		(washington)
- St. Patrick's Day						(stpatricks)
- Easter										(easter)
- May the 4th Be With You				(may4)
- Cinco de Mayo							(may5)
- Memorial Day								(memorial)
- Independence Day						(independence)
- Labor Day									(labor)
- Columbus Day								(columbus)
- Veterans Day								(veterans)
- Halloween									(halloween)
- Thanksgiving								(thanksgiving)
- Christmas									(christmas)

**CANADA HOLIDAYS**
- New Years Day							(newyear)
- Valentines								(valentines)
- Family Day								(family)
- St. Patrick's Day						(stpatricks)
- Easter										(easter)
- August Civic Holiday					(civic)
- Labour Day								(labour)
- Remembrance 							(remembrance)
- Thanksgiving 							(thanksgiving)
- Christmas									(christmas)
- Boxing Day								(boxing)

## Change Log

**0.2.1**
- Added Canada to supported countries. Yay Canada, eh?
- There are a couple Canadian holidays that are too complicated for the current setup to handle; I’m still trying to decide/figure out how to handle those.
- Documentation updates.

**0.2.0**
- Reworked the plugin to accept settings instead of separate tags for each country.

**0.1.0**
- First release
