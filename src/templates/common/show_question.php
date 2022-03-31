<?php $question_date =
  substr(date('F', strtotime($question['created_at'])), 0, 3) .
  ' ' .
  date('d', strtotime($question['created_at'])); ?>

<div class="profile-question">
  <div class="profile-question-content">
    <h3><?= htmlentities($question['content']) ?></h3>
  </div>
  <div class="profile-question-description">
    <span class="profile-question-username"><a href="profile.php?username=<?= $question['username'] ?>"><?= '@' . $question['username'] ?></a></span>
    <span class=" profile-question-date"><?= $question_date ?></span>
    <span class="profile-question-location"><?= $question['animal_location'] ?></span>
  </div>
</div>