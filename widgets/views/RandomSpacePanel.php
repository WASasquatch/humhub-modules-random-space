<?php
/**
 * View random space attached to sidebar on dashboard
 *
 * @package humhub-modules-randomspace
 * @author Jordan Thompson
 */
 
	$membership = $space->getMembership();
 
	// Membership Handling
	if ($space->isMember(Yii::app()->user->id)) {
		if ($space->isSpaceOwner(Yii::app()->user->id)) {
			$membershipText = Yii::t('RandomSpaceModuel.base', "You are the owner of this space.");
		} else {
			$membershipText = CHtml::link(Yii::t('RandomSpaceModuel.base', "Cancel membership"), $this->createUrl('//space/space/revokeMembership', array('sguid' => $space->guid)), array('class' => 'btn btn-danger'));
		}
	} else {
		if ($membership == null) {
			if ($space->canJoin()) {
				if ($space->join_policy == Space::JOIN_POLICY_APPLICATION) {
					$membershipText = CHtml::link(Yii::t('RandomSpaceModuel.base', 'Request membership'), $this->createUrl('//space/space/requestMembershipForm', array('sguid' => $space->guid)), array('class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => '#globalModal'));
				} else {
					$membershipText = '<a href="'.$this->createUrl('//space/space/requestMembership', array('sguid' => $space->guid)).'"
                                           class="btn btn-primary">'.Yii::t('RandomSpaceModuel.base', 'Become member').'</a>';
				}
			}
		} elseif ($membership->status == SpaceMembership::STATUS_INVITED) {
			$membershipText = '<a href="' . Yii::app()->createUrl("//space/space/inviteAccept", array('sguid' => $space->guid)) . '" class="btn btn-primary">' . Yii::t('RandomSpaceModuel.base', 'Accept Invite') . '</a> ';
			$membershipText .= '<a href="' . Yii::app()->createUrl("//space/space/revokeMembership", array('sguid' => $space->guid)) . '" class="btn btn-primary">' . Yii::t('RandomSpaceModuel.base', 'Decline Invite') . '</a> ';
		} elseif ($membership->status == SpaceMembership::STATUS_APPLICANT) {
			$membershipText = '<a href="' . Yii::app()->createUrl("//space/space/revokeMembership", array('sguid' => $space->guid)) . '" class="btn btn-primary" id="membership_button">' . Yii::t('RandomSpaceModuel.base', 'Cancel pending membership application') . '</a>';
		}
	}
	
	// Follow Handling
	if (!($space->isMember())) {
		if ($space->isFollowedByUser()) {
			$followText = HHtml::postLink(Yii::t('RandomSpaceModuel.base', "Unfollow"), $space->createUrl('//space/space/unfollow'), array('class' => 'btn btn-danger'));
		} else {
			$followText = HHtml::postLink(Yii::t('RandomSpaceModuel.base', "Follow"), $space->createUrl('//space/space/follow'), array('class' => 'btn btn-success'));
		}
	}
 
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('RandomSpaceModule.base', '<strong>Space</strong> of the Moment'); ?>
		<span class="member-count pull-right"><?php echo Yii::t('RandomSpaceModule.base', 'Members:') . ' ' . number_format(count($members)); ?></span>
    </div>
	<div id="random-space-widget">
        <a class="pull-left" href="<?php echo $space->getUrl(); ?>">
			<img src="<?php echo $space->getProfileImage()->getUrl(); ?>" width="150" height="150"
				class="random-space-image" alt="150x150" data-src="holder.js/150x150" /><br>
		</a>
		<div class="space-title">
			<a href="<?php echo $space->getUrl(); ?>">
				<?php echo Helpers::trimText($space->name, 25); ?>
			</a>
		</div>
		<div class="space-description">
			<?php echo (!(empty($space->description))) ? Helpers::trimText($space->description, 125) : Yii::t('RandomSpaceModule.base', '<i>No description</i>'); ?>
		</div>
	</div>
	<div id="space-widget-members">
		<?php $limit = 14; $cur = 0;
		foreach($members as $member): 
			if ($limit >= $cur): ?>
				<a href="<?php echo $member->getProfileUrl(); ?>"> 
					<img src="<?php echo $member->getProfileImage()->getUrl(); ?>" class="media-object tt space-widget-member-image img-rounded pull-left" 
					style="width: 24px; height: 24px;" alt="24x24" data-src="holder.js/24x24" data-toggle="tooltip" data-placement="top" title="" 
					data-original-title="<strong><?php echo $member->displayName; ?></strong><br /><?php echo $member->profile->title; ?>" />
				</a>
		<?php 
				$cur++;
			endif;  
		endforeach; 
		?>
	</div>
	<div id="space-widget-controls">
		<div class="random-space-membership">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="100%" valign="middle">
						<?php 
							echo $membershipText; 
							echo(isset($followText)) ? $followText : ''; 
						?>
					</td>
					<td width="31" valign="middle">
						<div class="random-space-owner">
							<a href="<?php echo Yii::app()->createUrl('//user/profile', array('uguid' => $space->getSpaceOwner()->guid)); ?>" title="Owner"><img src="<?php echo $space->getSpaceOwner()->getProfileImage()->getUrl(); ?>" 
								class="media-object tt space-widget-member-image img-rounded pull-right" 
								style="width: 28px; height: 28px;" alt="28x28" data-src="holder.js/28x28" data-toggle="tooltip" data-placement="top" title="" 
								data-original-title="<strong><?php echo $space->getSpaceOwner()->displayName; ?></strong><br /><?php echo $space->getSpaceOwner()->profile->title; ?>" />
							</a>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<link type="text/css" rel="stylesheet" href="<?php echo $css; ?>/main.css" />
