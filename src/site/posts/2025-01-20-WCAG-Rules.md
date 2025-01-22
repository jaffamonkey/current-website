---
title: WCAG
intro: |
    WCAG 2.1 and WCAG 2.2 rules, explained by example.
date: 2025-01-20
tags:
    - Accessibility
    - WCAG
---

>` [WCAG 2.1](https://www.w3.org/TR/WCAG21/) and [WCAG 2.2](https://www.w3.org/TR/WCAG22/) rules, explained by example.

# WCAG 2.1

## 1.1.1 Non-Text Content
**Level**: A
Provide text alternatives for non-text content that serves the same purpose.
Always provide alternative options like audio or OTP (one time password) for CAPTCHA.
Always provide textual summary or description for complex charts and graphs apart from shot alt text.
Always use title attribute for additional info while using alt attribute in image links.

## 1.2.1 Audio-Only and Video-Only
**Level**: A
Provide an alternative to video-only and audio-only content.
Provide text transcripts for audio tracks.
Provide either text transcript or an audio track for a silent video.
If an audio is provided for video then text transcript is not required. (Exception)

## 1.2.2 Captions (Prerecorded)
**Level**: A
Provide captions for videos with audio.
Provide captions for the video that has audio.
If video is the alternative for text content then it doesn’t need captions. (Exception)
If possible provide captions in multiple languages as this helps users to choose the language they can follow.

## 1.2.3 Audio Description or Media Alternative
**Level**: A
Provide audio description or text transcript for videos with sound.
Provide audio descriptions if possible or provide text transcripts for the video.
Videos that rely on sound only don’t require audio descriptions.
Example: interviews, speeches.

## 1.2.4 Captions (Live)
**Level**: AA
**Level**: Add captions to live videos.
Provide captions for a live broadcast.
Use media players or broadcast platforms where live captioning is supported.

## 1.2.5 Audio Description (Pre-Recorded)
**Level**: AA
Provide audio descriptions for pre-recorded videos.
Provide audio descriptions where the visual aspect is not explained in the dialog of the video.
The audio description can either be separate from the original video or be an integral part of the video.
**Level**: Audio description must include scene changes, settings, actions that are described in dialogues and any other visual information that is not conveyed via a speech or dialog.
Use media players that support audio descriptions.

## 1.3.1 Info and Relationships
**Level**: A
Content, structure and relationships can be programmatically determined.
Emphasis – Use `<em>` and `<strong>` instead of using Italics and Bold texts to highlight important texts; use `<blockquote>` to mark quotations
Headings – Provide hierarchically logical heading markup for the contents.
Table – Provide HTML table markup and provide column headers for simple tables and column headers and row headers for complex tables.
Table – When using nested tables, consider the possibility of breaking the content into logical individual tables instead of nested tables.
Forms – Provide programmatic association of visible labels or appropriate accessible names to all the form elements.
Lists – Markup the contents that logically fall into a list as ordered or unordered list. Do not put huge text blocks which are otherwise paragraphs as lists.
Grouping – Provide grouping and group level labels to mark a group of form elements like radio buttons or checkboxes; use `<fieldset>` and legend to achieve grouping and group Association for native form elements; use ARIA to achieve the same where custom form controls are used; use native semantic markup frequently and ARIA sparingly.

## 1.3.2 Meaningful Sequence
**Level**: A
Present content in a meaningful order.
Make sure that content presented on the page is logical & intuitive.
Write HTML first & then manage design with CSS.
Make sure the visual order matches the DOM order.
Use headings, lists, paragraphs etc to mark your content.
Make sure your users can differentiate the navigation menus from main content.

## 1.3.3 Sensory Characteristics
**Level**: A
Instructions don’t rely solely on sensory characteristics.
While using shape and or location, provide visible labels/names to the controls.
When combining color and shape/size/location/orientation, provide off-screen text for screen reader users.
When using sound as a clue, combine it with text/color based clues.

## 1.3.4 Orientation
**Level**: AA
Your website adapts to portrait and landscape views.
Don’t restrict your application or website to just work in landscape or portrait mode.
Make sure to honor the device settings while displaying the application in landscape or portrait mode.

## 1.3.5 Identify Input Purpose
**Level**: AA
The purpose of input fields must be programmatically determinable.
Provide programmatically determinable input purpose.
Use appropriate token values while using autocomplete attributes.
Do not use autocomplete on input fields that do not ask for user data.

## 1.4.1 Use of Color
**Level**: A
Don’t use presentation that relies solely on colour.
Don’t use color as the sole method to convey information.
Make sure instructions/prompts provided in text don’t refer to color alone.
Make sure instructions are provided in text for graphs and charts where color is used to convey information.
Provide more than one visual clue that include common icons and colors to differentiate texts and user interface elements.

## 1.4.2 Audio Control
**Level**: A
Don’t play audio automatically.
Don’t play audio automatically if possible.
Make sure your audio is less than 3 seconds.
If audio is more than 3 seconds then provide a pause/stop or a mechanism to control the audio player volume from the overall system volume.
Make sure that focus is on the pause/stop or volume control as soon as the page opens if audio is playing automatically.

## 1.4.3 Contrast Minimum
**Level**: AA
Contrast ratio between text and background is at least 4.5:1.
Develop the style guide in such a way that all the texts that are crucial meet the minimum contrast.
Choose color schemes that are contrastive enough for everyone to see and read.
Provide a “Contrast” mode with the help of alternative CSS if you can’t design and develop the content with the minimum contrast requirement.

## 1.4.4 Resize Text
**Level**: AA
Text can be resized to 200% without loss of content or function.
Where browsers do not support or provide zoom functionality (IE6 as an example), provide alternative CSS for scaling purpose.
When zoomed to 200%, ensure there is minimal horizontal scrolling (a best practice).
Where lengthy user interface components or content like subject line of an email is there, truncate and provide ways along with the instructions to access the content.

## 1.4.5 Images of Text
**Level**: AA
Don’t use images of text.
Use CSS styled headings instead of Bitmap images.
Provide site-wide controls to customize the images of texts when there are dynamically generated images of texts.
Use CSS to specify spacing, alignment, color and the font family of any UI elements, their texts and icons, quotations etc.
Use keyboard generated symbols wherever possible instead of making them as images.

## 1.4.10 Reflow
**Level**: AA
Content retains meaning and function without scrolling in two dimensions.
Use Responsive Web Design (RWD) from the conception of design itself.
Use accessible links, modals, toggle type elements to show or hide content.
**Level**: Avoid horizontal scroll bars in 400% zoom.
**Level**: Avoid content overlaps, clipping, content loss and functionality loss.

## 1.4.11 Non-Text Contrast
**Level**: AA
The contrast between user interface components, graphics and adjacent colours is at least 3:1.
Ensure hit-areas and focus indicators have 3:1 contrast ratio with their inner or outer background.
Ensure the checked/unchecked states meet the 3:1 ratio against their adjacent color in order to distinguish the states.
Ensure parts of graphs and charts where color is the only way to decipher the information, the contrast ratio is met against adjacent colors.
Ensure appropriate color combinations are chosen and defined for UI elements and other graphical objects in the style guides and the design documents in order to avoid uncomfortable retrofitting.

## 1.4.12 Text Spacing
**Level**: AA
Content and function retain meaning when users change elements of text spacing.
Don’t use fixed containers in your CSS styles.
Make sure that content reflows without overlapping or text cut-offs.
Use relative units of font size, line height, spaces between characters, words, lines and paragraphs.

## 1.4.13 Content on Hover or Focus
**Level**: AA
When hover or focus triggers content to appear, it is dismissible, hoverable and persistent.
Provide a method to dismiss the additional content that appears on hover or keyboard focus.
Make sure that content is present until the user moves away the mouse pointer from the triggering element or content block.
Make sure that experience is persistent.

## 2.1.1 Keyboard
**Level**: A
**Level**: All functionality is accessible by keyboard with no specific timings.
Make sure all elements on the page buttons, links, form controls etc. are reachable by tab key.
Make sure that users are able to activate the buttons, links and form controls using the enter and/or spacebar keys.
Write clean HTML & CSS as it is keyboard operable by default and doesn’t require any special tweaks.
Make sure that there is a visible focus for all the active elements on the page.
Make sure that focus order is logical and intuitive. Provide tabindex=0 for custom UI elements so that they are focusable.
Provide appropriate event handlers for custom scripted elements so that they are operable by their respective keys.
**Level**: Avoid access keys if possible. If not, at least, ensure they don’t conflict with the user agent and/or AT shortcut keys.
Make sure that there is no time limit to perform any action using the keyboard when more than one key is required to operate a control.

## 2.1.2 No keyboard Trap
**Level**: A
Users can navigate to and from all content using a keyboard.
Make sure that users can tab to and away from all parts of the site.
If a user is trapped on a portion of the web page for a purpose, a clear instruction must be provided for the user to end that keyboard trap.
Check if all parts of the site are operable using only the keyboard, test by unplugging the mouse.
Stick to standard navigation as much as possible like tab, shift+tab & arrow keys.
If custom keystrokes are provided to operate a control make sure hints are exposed to all users.
Make sure your third party widgets are accessible, most of the time they cause major keyboard operability issues.

## 2.1.4 Character Key Shortcuts
**Level**: A
**Level**: Allow users to turn off or remap single-key character shortcuts.
Don’t use single character key shortcuts if possible.
Provide a mechanism to turn off the character key shortcuts.
Design all the keyboard shortcuts with the combinations of non-printable keys.
Let the user trigger the shortcut key only when the element has keyboard focus.

## 2.2.1 Timing Adjustable
**Level**: A
Provide user controls to turn off, adjust or extend time limits.
Provide a control on the landing page for the user to initiate a longer session time or no session timeout.
Provide a way for the user to turn off the session timeout.
Provide a means to set the time limit to 10 times the default time limit.
Prompt the user with help of a pop-up or modal so that enough warning is available for the user to reset the time limit.
Make sure controls provided to extend the time limit are keyboard operable.
Moving, scrolling and/or blinking content must have a mechanism to pause or stop the movement or scroll or blink.
**Level**: Auto updating content must be provided with a feature to extend the time limit to 10 times of its actual update frequency.

## 2.2.2 Pause, Stop, Hide
**Level**: A
Provide user controls to pause, stop and hide moving and auto-updating content.
**Level**: Avoid moving, blinking scrolling content if possible.
Content should not blink more than 3 times per second, if it does blink 3 times per second then it is considered as flashing & will fail WCAG.
**Level**: Auto updating content should be provided with a pause button or provide a mechanism for the user to specify when the update can happen.
If the entire page contains moving, blinking, scrolling & auto updating content then pause, stop or hide buttons are not required as there is no parallel content.
**Level**: Animation that conveys the users that a page or content is loading doesn’t require to meet this success criterion.

## 2.3.1 Three Flashes or Below
**Level**: A
No content flashes more than three times per second.
**Level**: Avoid flashing content completely if possible.
Make sure that flashing content doesn’t flash more than 3 times per second.
Use PEAT to confirm if the flashing content passes this check point.

## 2.4.1 Bypass Blocks
**Level**: A
Provide a way for users to skip repeated blocks of content.
Provide a skip link on top of the page to skip navigational menus.
Provide skip links to navigate to content on a large page.
Make sure that skip link is visible when it receives focus.
Make sure that the purpose of the link is clear, provide skip link text as skip to main content or skip navigation etc.
When providing ARIA landmarks, ensure multiple landmarks of the same type are not provided.
If provided, ensure to use content-description to assign unique names to such landmarks “Primary navigation”, “secondary navigation” etc.

## 2.4.2 Page Titled
**Level**: A
Use helpful and clear page titles.
Provide a unique title.
Make sure that title is between 50-75 characters.
Make sure the title of the page is the heading level H1 on the page.
Title should contain web page name, bit of description and site name.
Make sure the title describes the purpose of the page.

## 2.4.3 Focus Order
**Level**: A
Components receive focus in a logical sequence.
**Level**: Avoid using tabindex values that are >`1 to manage focus order as they may supersede logical tab order.
**Level**: Align the focus order with the reading order as much as possible in order to maintain a logical and intuitive navigation of the content.
Too much deviation would put a lot of users with disabilities into confusion.

## 2.4.4 Link Purpose (In Context)
**Level**: A
Every link’s purpose is clear from its text or context.
Let the purpose of the link be clear just by the link text alone! E.G. “My Blog”, “Visit our Blog”.
Ensure appropriate alt text is provided when only an image stands as a link.
**Level**: Avoid ambiguous links like “here” or “click here”. If they are required make sure that they are placed at the end of the sentence or paragraph so that they are understood from context.
Do not duplicate the alt text and the link text when there is an image and the link adjacent to each other and convey the same info or lead to the same destination. Rather, wrap the image with the link and provide alt=”” for the image.
Ensure Links having the same link text leads to the same destination.

## 2.4.5 Multiple Ways
**Level**: AA
Offer at least two ways to find pages on your website.
More than one way must be available to meet this success criteria.
Though breadcrumb is quite old, it still works if the users want to go back in a process or a layered structure.
Search function is the most powerful to achieve faster navigation.
Menus may become larger and cumbersome; still they work wonders when you look up for a category.

## 2.4.6 Headings and Labels
**Level**: AA
Headings and labels describe topic or purpose.
Headings must be clear, concise & descriptive.
Headings must follow a sequential order to avoid confusion.
Ensure that headings are consistent throughout the site.
Ensure that labels are descriptive enough so that users can take necessary actions.

## 2.4.7 Focus Visible
**Level**: AA
Keyboard focus is visible when used.
Let browsers handle the visible focus for active elements.
Ensure that the active element is provided with visible focus.
Ensure that when the user is navigating through the page using keyboard visible focus moves along for every element presented on the page.
Ensure that there is sufficient contrast between the visible focus and the background of the element, for example if the visible focus is black and the background of the element is black then focus visible is not visually distinguishable.

## 2.4.11 Focus Not Obscured (Minimum)
**Level**: AA
When a user interface component is selected, the focus indicator encompasses the visual presentation of the component, maintains a contrast ratio of at least 3:1 between its pixels in focused and unfocused states, and ensures a contrast ratio of at least 3:1 against adjacent colors.
Visibility is Key: Make certain that all elements remain visible when they receive keyboard focus and are not obscured by other content blocks.
Partial Focus: In cases where maintaining full visibility of an element is not feasible, ensure a partial focus indicator is visible to the user.
Sticky Elements and CSS Scroll-Padding: When utilizing sticky elements, utilize the CSS scroll-padding property. This property enables you to set padding on all sides of an element simultaneously.

## 2.5.1 Pointer Gestures
**Level**: A
Multi-point and path-based gestures can be operated with a single pointer.
Do not use multi-pointer or path-based gestures as a sole method to control content.
Provide single tap or double tap/click as alternatives.
Always have in mind that one mode does fit for all.

## 2.5.2 Pointer Cancellation
**Level**: A
Functions don’t complete on the down-click of a pointer.
Ensure down event alone does not execute any functionality.
Ensure Up event reverses or un-does any down event-based action.
Ensure a mechanism is available to confirm the performed action where the down event executes such action.

## 2.5.3 Label in Name
**Level**: A
Where a component has a text label, the name of the component also contains the text displayed.
Ensure the accessible names like content-description and alt attribute contain the exact match of the visible label.
Ensure the visible label text and accessible name text are not interspersed.
Ensure the accessible name starts exactly with the visible name.

## 2.5.4 Motion Actuation
**Level**: A
Functions operated by motion can also be operated through an interface and responding to motion can be disabled.
Provide alternatives where motion actuation is used.
Provide confirmation or canceling mechanism.
**Level**: Allow system settings to deactivate motion detection.

## 2.5.7 Dragging Movements
**Level**: AA
**Level**: All functionality that uses a dragging movement for operation can be achieved by a single pointer without dragging, unless dragging is essential or the functionality is determined by the user agent and not modified by the author.
Ensure Alternative Functionality: Wherever drag and drop functionality is employed, it’s crucial to provide an alternative method. This ensures that users with diverse abilities can interact with the interface effectively.
Simplify Single Pointer or Touch Interaction: Ensure that interactions involving a single pointer or touch are intuitive and don’t burden users with excessive cognitive load. Simplify actions to enhance user experience and ease of use.
Simplicity in Design: Keep the user experience simple. During the design process, evaluate whether such complex functionalities, like intricate drag and drop features, are truly necessary for the product. Strive for elegance in simplicity to enhance user understanding and engagement.

## 2.5.8 Target Size (Minimum)
**Level**: AA
Ensure the target of any UI element has 24 by 24 CSS PX target size or there is enough spacing provided between two targets that have undersize targets.
Always provide a bigger target size (24 by 24 CSS PX) for the target areas of UI controls.
Provide enough spacing between elements on the side, above and below so that users do not touch or activate elements accidentally.
Design the layouts and the UI controls in such a way that there is always space between controls.

## 3.1.1 Language of Page
**Level**: A
Each webpage has a default human language assigned.
Ensure each page of your web site has a lang attribute.
Ensure the language code is correct.
Use appropriate language tokens in terms of language variations like lang=”en-us” for English in US and lang=”en-uk” for English in Britain.

## 3.1.2 Language of Parts
**Level**: AA
Each part of a webpage has a default human language assigned.
Ensure to use appropriate language code (lang=”fr”) wherever the text is in other language.
Ensure appropriate language token is used in the lang attribute (lang=”pt-br”). Beware of the exceptions too.

## 3.2.1 On Focus
**Level**: A
Elements do not change when they receive focus.
Ensure that no element changes by receiving focus.
We should avoid both visual and behavioral modifications to the page.
**Level**: A website built using only HTML and CSS will not have on focus by default, one needs to provide this through scripting.
One way to test this check point is to unplug the mouse and navigate the page using the keyboard.

## 3.2.2 On Input
**Level**: A
Elements do not change when they receive input.
Make sure that forms don’t submit on input of data.
Make sure that focus doesn’t move to next form control once a form field is populated with data.
Provide a submit button for all forms. Make sure that control of how data is populated is in the hands of your users.
If there is a change of context, then provide an instruction that is available for all user groups.

## 3.2.3 Consistent Navigation
**Level**: AA
Position menus and standard controls consistently.
Keep navigational menus in the same location.
Present the navigational menus in the same order on all pages.
Represent all the standard elements like logo, search functionality, and skip links etc. in the same location on all the pages.
Using a standard template will help achieve the success criteria of 3.2.3 consistent navigation.

## 3.2.4 Consistent Identification
**Level**: AA
Identify components with the same function consistently.
Icons and images that are used repeatedly and provide the same function must be provided with the same alternative text.
Elements with the same function are named and labeled consistently.
Use icons/images that are consistent. For example print, twitter, Facebook etc.
Images will have different meaning in different contexts, so will need different alternative text depending on the context.

## 3.2.6 Consistent Help
**Level**: A
Help options are presented programmatically in the same order.
It is always best to provide specific help mechanisms like human contact, self-help etc. for users to complete complex tasks.
Provide such help mechanisms in the same order that is consistent across a set of web pages.
Ensure help mechanisms across the entire site are consistently and easily locatable.

## 3.3.1 Error Identification
**Level**: A
Identify and describe input errors for users.
Make sure that errors are in text. Don’t just use color or visual cues to point out form errors.
Use aria-describedby to bind the form control with the error programmatically.
Don’t disable the submit button! Some websites disable the submit button & will only enable it if the form is filled appropriately. This is bad practice.
Provide necessary instructions and be as specific as possible with the errors so that users can take necessary action.
Make sure that errors are distinguished from the regular text on the web page.

## 3.3.2 Labels or Instruction
**Level**: A
Provide labels or instructions for user input.
Always provide visible labels to every form field and controls.
Provide instructions where the form fields require specific data or format.
Ensure the labels identify the fields clearly.
Do programmatically associate the labels with their respective fields.
Provide group level labels and associate them with the group of form fields where the user input is required in more than one field like phone number or credit card number; also ensure to provide individual labels through title attribute in such scenarios.

## 3.3.3 Error Suggestion
**Level**: AA
Suggest corrections when users make mistakes.
Provide descriptive errors.
Provide visible hints that will enable the users to avoid errors during form submissions.
**Level**: Associate the errors with form controls using aria-describedby.
Move the focus to the form control that has the error once validation failed, this reduces the number of keystrokes.
Mark the required fields with an asterisk visually and programmatically with aria-required.

## 3.3.4 Error Prevention (Legal, Financial, Data)
**Level**: AA
Check, confirm and allow reversal of pages that cause important commitments.
Make sure proper hints are provided to fill the data in the forms.
Provide a review information screen where user provided information is populated.
Provide a checkbox where the user can confirm that they have reviewed all the information and they are ready to submit; enable the submit button only when the user checks the checkbox.
Provide confirmation screen or dialogue when users delete any data.

## 3.3.7 Redundant Entry
**Level**: A
**Level**: Autofill form- fields that repeat across steps.
**Level**: Avoid asking users to re-enter the same information during a process.
Provide mechanisms where users can select the information that they previously entered.
Remember that the information users are copying or pasting to avoid retyping must be on the same page.

## 3.3.8 Accessible Authentication (Minimum)
**Level**: AA
It states that users must be able to access authentication methods using only a keyboard. This means that the authentication process should not require the use of a mouse or other pointing device.
Design authentication processes that are accessible and secure.
Provide alternatives for users when a certain authentication process involves too much of a cognitive burden.
Involve more modern and sophisticated techniques such as biometric authentications and Magic Link (A one-time link sent to a user’s email or SMS to be clicked).
Always provide a proper name, role value, and input purpose like autocomplete attribute to username and password fields.

## 4.1.2 Name, Role, Value
**Level**: A
The name and role of user components can be understood by technology.
Use native HTML elements wherever possible.
USE WAI-ARIA attributes while constructing custom component widgets.
Make sure custom widgets are keyboard operable using spacebar or enter keys.
Provide tabindex=0 for custom widgets so that they receive tab focus.
Make it a practice to read ARIA specifications to understand the implications and the consequences of ARIA roles, states and properties before using them

## 4.1.3 Status Messages
**Level**: AA
Make sure that all messages indicating success or errors are read out by a screen reader.
Ensure all success toasts and error messages are announced by screen reader.
Do not fill the pages with live regions.
Decide which is an important update and qualifies a status message intelligently.
Ensure focusable messages are not considered as status messages.

# WCAG 2.2

## 2.4.11 Focus Not Obscured
**Level**: AA
When a user interface component receives keyboard focus, at least a portion of it must remain visible and not be hidden by other content you provide.

## 2.4.12 Focus Not Obscured
**Level**: AAA
When a user interface component receives keyboard focus, none of the focus indicator may be hidden by your content. This is the AAA level of success criterion 2.4.11 listed previously.

## 2.4.13 Focus Appearance
**Level**: AAA
Focus indicators must have sufficient color contrast between the focused and unfocused states and must be of a sufficient size so as to be clearly visible.

## 2.5.7 Dragging Movements
**Level**: AA
If any part of your website requires a dragging movement, provide an alternative means of dragging, such as tapping or clicking. For example, instead of dragging a map, the interface could offer buttons that move the map in a particular direction.

## 2.5.8 Target Size
**Level**: AA
**Level**: All interactive targets should be at least 24Ã—24 CSS pixels in size. This can include padding within the target. Additionally, there must be sufficient space between targets.

## 3.2.6 Consistent Help
**Level**: A
If you make a help option available, make sure it’s available consistently, and in the same relative place. This will make it easier to locate while navigating your website.

## 3.3.7 Redundant Entry
**Level**: A
In a process, such as registering or completing a form, information that the user has already entered must be made available to them. This helps users by not making them enter information more than once unless it’s absolutely necessary.

## 3.3.8 Accessible Authentication
**Level**: A
If your site requires a cognitive test, such as memorizing a username and password in order to log in, there needs to be a different way to authenticate that doesn’t require the ability to do that, or a help mechanism needs to be made available to assist with that. For example, a username and password field that allows for entry by a password manager provides assistance, as would allowing for the user to paste into the fields. At this level, a cognitive function test that requires the recognition of an object, like a stop sign, is allowed, as is a test that asks a user to identify a picture or image the user provided to the website.

## 3.3.9 Accessible Authentication
**Level**: AAA
Users shouldn’t be forced to memorize information or necessarily spell correctly. Those and other tasks are considered cognitive tests. If an authentication process has a cognitive function test in a step, the site needs to provide an alternative that doesn’t or provide a help mechanism to complete the test. In addition—and this is a key difference between SC 3.3.8 and 3.3.9—authentication by using object recognition or user-provided content (e.g., a picture uploaded by the user) isn’t permitted at this level.