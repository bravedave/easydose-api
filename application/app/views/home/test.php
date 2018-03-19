<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/ ?>
  <style>
  .date-helper { display: none; }
  .date-helper-mouseover .date-helper,
  .date-helper-focused .date-helper { display: block; }
  </style>

  <form>
    <div class="form-group">
      <label for="event-date">Date</label>
      <input type="text" class="form-control" name="event-date"
        id="event-date"
        data-dateformat="L" aria-describedby="eventDate-help" data-provide="date-helper">
      <small id="eventDate-help" class="form-text text-muted">Enter a date bucko</small>

    </div>

    <div class="form-group">
      <label for="event-name">Name</label>
      <input type="text" class="form-control" name="event-name"
      id="event-name" aria-describedby="eventName-help">
      <small id="eventName-help" class="form-text text-muted">Enter a event name</small>

    </div>

  </form>
