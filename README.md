# 334Project
COMP-334 Web Design final project


Seaside Hotels Reservation System.

Start at form.php, then log in either using username "testguest" and password "guestpass" to reach the guest page, or using username "testfrontdesk" and password "frontdeskpass" to reach the front desk/reception page.

Guests can:
- update their information (interacts with the guests table while hiding all information and outputting a message if successfully updated)
- search for rooms that meet their needs by entering a start date and end date (in the form YYYY-MM-DD) and then any other desired specifiers (e.g. Room or Suite, smoking or non-smoking, price floor/ceiling).
- Check and make reservations in the resulting HTML table, which will reserve the checked rooms for the previously-established date span
- View their own reservations
- Select and cancel any of their own reservations (deleting it from the reservations table)

Front desk can:
- Enter a guest's name or ID (if you want to check against reservations you've made/cancelled on the guests page, either enter 1 (for guest_id 1) in this field, or update the name on the guest page and then type the new name on the front desk page.  This can also be used to confirm a prior update to name under guest info.  All other guest information can also be updated on the guest page, but it is considered private and is not accessible to front desk staff.
This will produce a list of that guest's reservations.  Front desk can then:
- Extend that reservation's end date
- Move the reservation to a different room (including allowing the guest to share rooms with another guest, if desired)
- "Check out" a guest, deleting the checked reservation from the reservations table.

Guest and Front desk can both log out and return to the login screen, and then you can log in as the other role by entering the appropriate username and password.

Currently guest 1 has 2 reservations, which can be viewed (and cancelled) if desired.

Three other guests exist (guest id 2 3 4 respectively) who have reservations already in the system, making those rooms unavailable on those dates.  Enter 2, 3, or 4 on the front desk view page to view their names, reserved rooms, and dates they have rooms reserved.  Front desk can extend their stay, move their rooms around, or check them out just like with guest 1 (although new reservations cannot be added for them, as they do not have logins for the guest page).
